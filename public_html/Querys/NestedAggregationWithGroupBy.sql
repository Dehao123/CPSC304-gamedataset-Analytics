SELECT ChampionName, AVG(MatchupWinrate) AS ChampionAvgWinrate
FROM Matchup
GROUP BY ChampionName
HAVING AVG(MatchupWinrate) > (
    SELECT AVG(MatchupWinrate)
    FROM Matchup
);