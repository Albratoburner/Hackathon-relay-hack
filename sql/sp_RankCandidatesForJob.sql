IF OBJECT_ID('dbo.sp_RankCandidatesForJob', 'P') IS NOT NULL
    DROP PROCEDURE dbo.sp_RankCandidatesForJob;
GO

CREATE PROCEDURE dbo.sp_RankCandidatesForJob
    @JobId INT,
    @RequiredSkillsJson NVARCHAR(MAX),
    @MaxResults INT = 10
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ExecutionStart DATETIME2 = SYSDATETIME();
    DECLARE @ErrorMessage NVARCHAR(4000);
    DECLARE @MinExperienceYears INT;
    DECLARE @PreferredLocation NVARCHAR(50);
    DECLARE @AvailabilityType NVARCHAR(20);

    BEGIN TRY
        IF NOT EXISTS (SELECT 1 FROM dbo.job_orders WHERE job_id = @JobId AND status = 'open')
        BEGIN
            RAISERROR('Job ID %d not found or not open', 16, 1, @JobId);
            RETURN;
        END;

        SELECT
            @MinExperienceYears = min_experience_years,
            @PreferredLocation  = preferred_location,
            @AvailabilityType   = availability_type
        FROM dbo.job_orders
        WHERE job_id = @JobId;

        IF @RequiredSkillsJson IS NULL OR LTRIM(RTRIM(@RequiredSkillsJson)) = ''
            SET @RequiredSkillsJson = N'[]';

        CREATE TABLE #RequiredSkills (skill_name NVARCHAR(50), weight INT, tier NVARCHAR(20), min_level INT);
        INSERT INTO #RequiredSkills
        SELECT JSON_VALUE(value, '$.skillName'),
               CAST(JSON_VALUE(value, '$.weight') AS INT),
               JSON_VALUE(value, '$.tier'),
               CAST(JSON_VALUE(value, '$.minLevel') AS INT)
        FROM OPENJSON(@RequiredSkillsJson);

        DECLARE @TotalWeight INT      = (SELECT ISNULL(SUM(weight), 0) FROM #RequiredSkills);
        DECLARE @TotalSkillCount INT  = (SELECT COUNT(*) FROM #RequiredSkills);

        CREATE TABLE #CandidatePool (
            candidate_id INT PRIMARY KEY,
            full_name NVARCHAR(100),
            email NVARCHAR(100),
            location NVARCHAR(50),
            availability_type NVARCHAR(20),
            total_experience_years INT
        );

        INSERT INTO #CandidatePool
        SELECT candidate_id, full_name, email, location, availability_type, total_experience_years
        FROM dbo.candidates
        WHERE is_active = 1;

        CREATE TABLE #SkillScores (candidate_id INT PRIMARY KEY, skill_score DECIMAL(5,2), matched_skills_count INT);

        INSERT INTO #SkillScores (candidate_id, skill_score, matched_skills_count)
        SELECT
            cp.candidate_id,
            CASE WHEN @TotalWeight > 0 THEN
                CAST((ISNULL(SUM(rs.weight * (cs.proficiency_level / 5.0) * ISNULL(sl.multiplier, 1.0) *
                    (1 + (CASE WHEN cs.years_of_experience > 5 THEN 5 ELSE ISNULL(cs.years_of_experience, 0) END) * 0.05)
                ), 0) / @TotalWeight) * 40.0 AS DECIMAL(5,2))
            ELSE 20.0 END,
            ISNULL(COUNT(DISTINCT rs.skill_name), 0)
        FROM #CandidatePool cp
        LEFT JOIN dbo.candidate_skills cs ON cp.candidate_id = cs.candidate_id
        LEFT JOIN dbo.skills s ON cs.skill_id = s.skill_id
        LEFT JOIN dbo.skill_levels sl ON cs.level_id = sl.level_id
        LEFT JOIN #RequiredSkills rs ON s.skill_name = rs.skill_name AND cs.proficiency_level >= rs.min_level
        GROUP BY cp.candidate_id;

        CREATE TABLE #ExperienceScores (candidate_id INT PRIMARY KEY, experience_score DECIMAL(5,2));

        INSERT INTO #ExperienceScores (candidate_id, experience_score)
        SELECT
            candidate_id,
            CASE
                WHEN total_experience_years < @MinExperienceYears THEN
                    CAST(CASE WHEN 15 + ((@MinExperienceYears - total_experience_years) * -5) < 0
                         THEN 0 ELSE 15 + ((@MinExperienceYears - total_experience_years) * -5) END AS DECIMAL(5,2))
                ELSE
                    CAST(CASE
                        WHEN 15 +
                            CASE WHEN total_experience_years - @MinExperienceYears <= 3
                                THEN (total_experience_years - @MinExperienceYears) * 3
                            WHEN total_experience_years - @MinExperienceYears <= 5
                                THEN 9 + ((total_experience_years - @MinExperienceYears - 3) * 2)
                            ELSE 13 + ((total_experience_years - @MinExperienceYears - 5) * 1)
                            END +
                            CASE WHEN total_experience_years >= (@MinExperienceYears * 2) THEN 5 ELSE 0 END
                        > 25 THEN 25
                        ELSE 15 +
                            CASE WHEN total_experience_years - @MinExperienceYears <= 3
                                THEN (total_experience_years - @MinExperienceYears) * 3
                            WHEN total_experience_years - @MinExperienceYears <= 5
                                THEN 9 + ((total_experience_years - @MinExperienceYears - 3) * 2)
                            ELSE 13 + ((total_experience_years - @MinExperienceYears - 5) * 1)
                            END +
                            CASE WHEN total_experience_years >= (@MinExperienceYears * 2) THEN 5 ELSE 0 END
                    END AS DECIMAL(5,2))
            END
        FROM #CandidatePool;

        CREATE TABLE #AvailabilityScores (candidate_id INT PRIMARY KEY, availability_score DECIMAL(5,2), current_availability NVARCHAR(100));

        ;WITH ActiveAssignments AS (
            SELECT
                candidate_id,
                end_date,
                status,
                ROW_NUMBER() OVER (PARTITION BY candidate_id
                    ORDER BY CASE WHEN end_date IS NULL THEN 0 ELSE 1 END, end_date DESC) AS rn
            FROM dbo.candidate_assignments
            WHERE (end_date IS NULL OR end_date > GETDATE())
              AND status = 'active'
        )
        INSERT INTO #AvailabilityScores (candidate_id, availability_score, current_availability)
        SELECT
            cp.candidate_id,
            CASE
                WHEN aa.candidate_id IS NULL THEN
                    CAST(20.0 - CASE WHEN cp.availability_type <> @AvailabilityType THEN 4.0 ELSE 0.0 END AS DECIMAL(5,2))
                WHEN aa.end_date IS NOT NULL THEN
                    CAST(
                        CASE
                            WHEN DATEDIFF(DAY, GETDATE(), aa.end_date) > 90 THEN 0
                            WHEN DATEDIFF(DAY, GETDATE(), aa.end_date) > 60 THEN 8
                            WHEN DATEDIFF(DAY, GETDATE(), aa.end_date) > 30 THEN 12
                            WHEN DATEDIFF(DAY, GETDATE(), aa.end_date) > 14 THEN 15
                            ELSE 18
                        END
                        - CASE WHEN cp.availability_type <> @AvailabilityType THEN 4.0 ELSE 0.0 END
                    AS DECIMAL(5,2))
                ELSE 0.0
            END,
            CASE
                WHEN aa.candidate_id IS NULL THEN 'Immediate'
                WHEN aa.end_date IS NULL THEN 'Long-term assignment (unavailable)'
                ELSE 'Available ' + FORMAT(aa.end_date, 'yyyy-MM-dd')
            END
        FROM #CandidatePool cp
        LEFT JOIN ActiveAssignments aa ON cp.candidate_id = aa.candidate_id AND aa.rn = 1;

        CREATE TABLE #LocationScores (candidate_id INT PRIMARY KEY, location_score DECIMAL(5,2));

        INSERT INTO #LocationScores (candidate_id, location_score)
        SELECT
            candidate_id,
            CAST(CASE
                WHEN LOWER(location) = LOWER(@PreferredLocation) THEN 10
                WHEN location LIKE '%' + @PreferredLocation + '%' OR @PreferredLocation LIKE '%' + location + '%' THEN 7
                WHEN location LIKE '%Remote%' OR location LIKE '%Anywhere%' OR @PreferredLocation LIKE '%Remote%' THEN 5
                ELSE 0
            END AS DECIMAL(5,2))
        FROM #CandidatePool;

        CREATE TABLE #CulturalFitScores (candidate_id INT PRIMARY KEY, cultural_fit_score DECIMAL(5,2));

        ;WITH HistoricalPerformance AS (
            SELECT
                ca.candidate_id,
                COUNT(*) AS total_assignments,
                COUNT(CASE WHEN ca.status = 'completed' THEN 1 END) AS completed_count,
                CAST(COUNT(CASE WHEN ca.status = 'completed' THEN 1 END) AS FLOAT) / NULLIF(COUNT(*), 0) AS completion_rate,
                AVG(CAST(cpr.rating AS FLOAT)) AS avg_rating
            FROM dbo.candidate_assignments ca
            LEFT JOIN dbo.candidate_performance_reviews cpr ON ca.assignment_id = cpr.assignment_id
            WHERE ca.candidate_id IN (SELECT candidate_id FROM #CandidatePool)
            GROUP BY ca.candidate_id
        )
        INSERT INTO #CulturalFitScores (candidate_id, cultural_fit_score)
        SELECT
            cp.candidate_id,
            CAST(CASE
                WHEN hp.candidate_id IS NULL THEN 2.0
                WHEN hp.completion_rate > 0.9 AND hp.avg_rating > 4.0 THEN 5.0
                WHEN hp.completion_rate > 0.7 AND hp.avg_rating >= 3.0 THEN 3.0
                WHEN hp.completion_rate > 0.5 THEN 2.0
                ELSE 1.0
            END AS DECIMAL(5,2))
        FROM #CandidatePool cp
        LEFT JOIN HistoricalPerformance hp ON cp.candidate_id = hp.candidate_id;

        CREATE TABLE #FinalRankings (
            rank_position INT,
            candidate_id INT,
            full_name NVARCHAR(100),
            email NVARCHAR(100),
            location NVARCHAR(50),
            total_score DECIMAL(5,2),
            skill_score DECIMAL(5,2),
            experience_score DECIMAL(5,2),
            availability_score DECIMAL(5,2),
            location_score DECIMAL(5,2),
            cultural_fit_score DECIMAL(5,2),
            years_experience INT,
            matched_skills_count INT,
            total_required_skills INT,
            current_availability NVARCHAR(100),
            recommendation NVARCHAR(255)
        );

        ;WITH ScoredCandidates AS (
            SELECT
                cp.candidate_id,
                cp.full_name,
                cp.email,
                cp.location,
                cp.total_experience_years,
                (ss.skill_score + es.experience_score + avs.availability_score + ls.location_score + cfs.cultural_fit_score) AS total_score,
                ss.skill_score,
                es.experience_score,
                avs.availability_score,
                ls.location_score,
                cfs.cultural_fit_score,
                ss.matched_skills_count,
                avs.current_availability
            FROM #CandidatePool cp
            INNER JOIN #SkillScores ss ON cp.candidate_id = ss.candidate_id
            INNER JOIN #ExperienceScores es ON cp.candidate_id = es.candidate_id
            INNER JOIN #AvailabilityScores avs ON cp.candidate_id = avs.candidate_id
            INNER JOIN #LocationScores ls ON cp.candidate_id = ls.candidate_id
            INNER JOIN #CulturalFitScores cfs ON cp.candidate_id = cfs.candidate_id
            WHERE avs.availability_score > 0
        )
        INSERT INTO #FinalRankings
        SELECT TOP (@MaxResults)
            ROW_NUMBER() OVER (ORDER BY total_score DESC, skill_score DESC, total_experience_years DESC, candidate_id ASC) AS rank_position,
            candidate_id,
            full_name,
            email,
            location,
            total_score,
            skill_score,
            experience_score,
            availability_score,
            location_score,
            cultural_fit_score,
            total_experience_years,
            matched_skills_count,
            @TotalSkillCount,
            current_availability,
            CASE
                WHEN matched_skills_count = @TotalSkillCount AND availability_score = 20 AND location_score = 10 THEN
                    'Excellent match - All skills matched with immediate availability'
                WHEN skill_score >= 32 AND availability_score >= 15 THEN
                    'Strong candidate - ' + CAST(ROUND((CAST(matched_skills_count AS FLOAT) / NULLIF(@TotalSkillCount, 1)) * 100, 0) AS NVARCHAR) + '% skill match, ' + current_availability
                WHEN experience_score >= 20 AND skill_score >= 24 THEN
                    'Good fit - Senior experience compensates for missing secondary skills'
                WHEN skill_score >= 28 THEN
                    'Solid candidate - Strong skill alignment, review availability'
                ELSE
                    'Consider with caution - Partial match, may need training'
            END
        FROM ScoredCandidates
        ORDER BY total_score DESC, skill_score DESC, total_experience_years DESC, candidate_id ASC;

        INSERT INTO dbo.ranking_history (
            job_id,
            candidate_id,
            rank_position,
            total_score,
            skill_score,
            experience_score,
            availability_score,
            location_score,
            cultural_fit_score,
            execution_date,
            selected_by_recruiter,
            created_at,
            updated_at
        )
        SELECT
            @JobId,
            candidate_id,
            rank_position,
            total_score,
            skill_score,
            experience_score,
            availability_score,
            location_score,
            cultural_fit_score,
            @ExecutionStart,
            0,
            @ExecutionStart,
            @ExecutionStart
        FROM #FinalRankings;

        SELECT *
        FROM #FinalRankings
        ORDER BY rank_position;

        PRINT 'Execution completed in ' + CAST(DATEDIFF(MILLISECOND, @ExecutionStart, SYSDATETIME()) AS NVARCHAR) + 'ms';
    END TRY
    BEGIN CATCH
        SET @ErrorMessage = ERROR_MESSAGE();
        RAISERROR('sp_RankCandidatesForJob failed: %s', 16, 1, @ErrorMessage);

        SELECT 0 AS rank_position, 0 AS candidate_id, '' AS full_name, '' AS email, '' AS location, 0.0 AS total_score,
               0.0 AS skill_score, 0.0 AS experience_score, 0.0 AS availability_score, 0.0 AS location_score, 0.0 AS cultural_fit_score,
               0 AS years_experience, 0 AS matched_skills_count, 0 AS total_required_skills, '' AS current_availability,
               'ERROR: ' + @ErrorMessage AS recommendation
        WHERE 1 = 0;
    END CATCH;

    DROP TABLE IF EXISTS #RequiredSkills, #CandidatePool, #SkillScores, #ExperienceScores, #AvailabilityScores, #LocationScores, #CulturalFitScores, #FinalRankings;
END;
GO
