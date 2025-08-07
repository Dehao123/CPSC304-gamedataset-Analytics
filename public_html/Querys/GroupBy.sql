SELECT Tier, COUNT(*) AS ChampionCount
FROM Champions
GROUP BY Tier;