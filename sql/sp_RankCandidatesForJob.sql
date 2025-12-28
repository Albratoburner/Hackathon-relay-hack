-- Created by GitHub Copilot in SSMS - review carefully before executing
DECLARE @JobId INT = (SELECT TOP 1 job_id FROM job_orders WHERE status = 'open');

DECLARE @SkillsJson NVARCHAR(MAX) = N'[
  {"skillName": "SQL Server", "weight": 10, "tier": "primary", "minLevel": 4},
  {"skillName": "Laravel", "weight": 8, "tier": "primary", "minLevel": 3},
  {"skillName": "React", "weight": 5, "tier": "secondary", "minLevel": 3},
  {"skillName": "Docker", "weight": 2, "tier": "bonus", "minLevel": 2}
]';

EXEC sp_RankCandidatesForJob 
    @JobId = @JobId,
    @RequiredSkillsJson = @SkillsJson,
    @MaxResults = 12;