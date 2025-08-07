SELECT Champions.ChampionName, Champions.Tier, Stats.Winrate, Stats.Pickrate, Stats.Banrate  
FROM Champions, Stats 
WHERE Champions.ChampionName = Stats.ChampionName;