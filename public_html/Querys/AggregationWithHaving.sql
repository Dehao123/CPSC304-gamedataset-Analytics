SELECT ChampionName, COUNT(*) AS BuildCount
FROM Build
GROUP BY ChampionName
HAVING COUNT(*) > 1;