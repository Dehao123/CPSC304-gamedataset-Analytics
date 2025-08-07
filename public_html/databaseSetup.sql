DROP TABLE BuildUsesItem;
DROP TABLE Matchup;
DROP TABLE Build;
DROP TABLE Stats;
DROP TABLE Champions;
DROP TABLE Item;
DROP TABLE Patch;
DROP TABLE Role;
DROP TABLE Esports_player1;
DROP TABLE Esports_player2;
DROP TABLE Esports_player3;
DROP TABLE Amateur_player;

CREATE TABLE Amateur_player (
    UserID INTEGER PRIMARY KEY,
    Rank VARCHAR(20),
    Preferred_role VARCHAR(10)
);

CREATE TABLE Esports_player1 (
   UserID INTEGER PRIMARY KEY,
   Rank VARCHAR(20),
   Team VARCHAR(30)
);

CREATE TABLE Esports_player2 (
   Team VARCHAR(30) PRIMARY KEY,
   Position VARCHAR(20)
);

CREATE TABLE Esports_player3 (
   Team VARCHAR(30) PRIMARY KEY,
   StageName VARCHAR(20)
);

CREATE TABLE Role (
   RoleName VARCHAR(20) PRIMARY KEY,
   Map_area VARCHAR(10)
);

CREATE TABLE Patch (
   PatchID DECIMAL(4,2) PRIMARY KEY,
   PatchDate VARCHAR(20)
);

CREATE TABLE Item (
   Name VARCHAR(50) PRIMARY KEY,
   Effect VARCHAR(200)
);

CREATE TABLE Champions (
   ChampionName VARCHAR(30) PRIMARY KEY,
   Archetype VARCHAR(30),
   Tier VARCHAR(10),
   RoleName VARCHAR(20),
   FOREIGN KEY (RoleName) REFERENCES Role ON DELETE CASCADE
);

CREATE TABLE Stats (
   ChampionName VARCHAR(50),
   Winrate DECIMAL(5,2),
   Pickrate DECIMAL(5,2),
   Banrate DECIMAL(5,2),
   PRIMARY KEY (ChampionName , Winrate),
   FOREIGN KEY (ChampionName) REFERENCES Champions ON DELETE CASCADE
);

CREATE TABLE Build (
   ChampionName VARCHAR(50),
   SkillOrder CHAR(3),
   PatchID DECIMAL(4,2) NOT NULL,
   PRIMARY KEY (ChampionName, SkillOrder),
   FOREIGN KEY (ChampionName) REFERENCES Champions ON DELETE CASCADE,
   FOREIGN KEY (PatchID) REFERENCES Patch ON DELETE CASCADE
);

CREATE TABLE Matchup (
   ChampionName VARCHAR(50),
   Counter_Champion VARCHAR(50),
   PatchID DECIMAL(4,2),
   MatchupWinrate DECIMAL(5,2),
   MatchesPlayed INTEGER,
   PRIMARY KEY (ChampionName, Counter_Champion),
   FOREIGN KEY (ChampionName) REFERENCES Champions ON DELETE CASCADE,
   FOREIGN KEY (PatchID) REFERENCES Patch ON DELETE CASCADE
);

CREATE TABLE BuildUsesItem (
   ChampionName VARCHAR(50),
   SkillOrder CHAR(3),
   ItemName VARCHAR(50),
   PRIMARY KEY (ChampionName, SkillOrder, ItemName),
   FOREIGN KEY (ChampionName, SkillOrder) REFERENCES Build ON DELETE CASCADE,
   FOREIGN KEY (ItemName) REFERENCES Item ON DELETE CASCADE
);


INSERT INTO Amateur_player (UserID, Rank, Preferred_role)
VALUES (101, 'Bronze', 'Mid');
INSERT INTO Amateur_player (UserID, Rank, Preferred_role)
VALUES (102, 'Silver', 'Top');
INSERT INTO Amateur_player (UserID, Rank, Preferred_role)
VALUES (103, 'Gold', 'Support');
INSERT INTO Amateur_player (UserID, Rank, Preferred_role)
VALUES (104, 'Platinum', 'Jungle');
INSERT INTO Amateur_player (UserID, Rank, Preferred_role)
VALUES (105, 'Diamond', 'ADC');


INSERT INTO Esports_player1 (UserID, Rank, Team)
VALUES (201, 'Challenger', 'GenG');
INSERT INTO Esports_player1 (UserID, Rank, Team)
VALUES (202, 'Challenger', 'T1');
INSERT INTO Esports_player1 (UserID, Rank, Team)
VALUES (203, 'Challenger', 'HLE');
INSERT INTO Esports_player1 (UserID, Rank, Team)
VALUES (204, 'Challenger', 'AL');
INSERT INTO Esports_player1 (UserID, Rank, Team)
VALUES (205, 'Challenger', 'BLG');


INSERT INTO Esports_player2 (Team, Position)
VALUES ('GenG', 'Top');
INSERT INTO Esports_player2 (Team, Position)
VALUES ('T1', 'Jungle');
INSERT INTO Esports_player2 (Team, Position)
VALUES ('HLE', 'Mid');
INSERT INTO Esports_player2 (Team, Position)
VALUES ('AL', 'ADC');
INSERT INTO Esports_player2 (Team, Position)
VALUES ('BLG', 'Support');
INSERT INTO Esports_player3 (Team, StageName)
VALUES ('GenG', 'Kiin');
INSERT INTO Esports_player3 (Team, StageName)
VALUES ('T1', 'Oner');
INSERT INTO Esports_player3 (Team, StageName)
VALUES ('HLE', 'Zeka');
INSERT INTO Esports_player3 (Team, StageName)
VALUES ('AL', 'Hope');
INSERT INTO Esports_player3 (Team, StageName)
VALUES ('BLG', 'ON');

INSERT INTO Role (RoleName, Map_area)
VALUES ('Top', 'Top Lane');
INSERT INTO Role (RoleName, Map_area)
VALUES ('Jungle', 'The Jungle');
INSERT INTO Role (RoleName, Map_area)
VALUES ('Mid', 'Mid Lane');
INSERT INTO Role (RoleName, Map_area)
VALUES ('ADC', 'Bot Lane');
INSERT INTO Role (RoleName, Map_area)
VALUES ('Support', 'Bot Lane');



INSERT INTO Patch (PatchID, PatchDate)
VALUES (25.14, '2025 - 07 - 15');
INSERT INTO Patch (PatchID, PatchDate)
VALUES (25.13, '2025 - 06 - 24');
INSERT INTO Patch (PatchID, PatchDate)
VALUES (25.12, '2025 - 06 - 10');
INSERT INTO Patch (PatchID, PatchDate)
VALUES (25.11, '2025 - 05 - 27');
INSERT INTO Patch (PatchID, PatchDate)
VALUES (25.10, '2025 - 05 - 13');


INSERT INTO Item (Name, Effect)
VALUES ('Infinity Edge', 'Massively enhances critical strikes.');
INSERT INTO Item (Name, Effect)
VALUES ('Luden''s Companion', 'Grants Ability Power, Mana, and 
         burst damage.');
INSERT INTO Item (Name, Effect)
VALUES ('Heartsteel', 'Grants stacking Health and HP scaling
         damage.');
INSERT INTO Item (Name, Effect)
VALUES ('Redemption', 'ACTIVE: Heals allies and damages
         enemies in a circle.');
INSERT INTO Item (Name, Effect)
VALUES ('Zhonya''s Hourglass', 'ACTIVE: Become invincible for the duration but unable to take action.');
INSERT INTO Item (Name, Effect)
VALUES ('Eclipse', 'Grants Attack damage and a shield.');


INSERT INTO Champions(ChampionName, Tier, Archetype, RoleName)
VALUES ('Ambessa', 'OP',
           'Fighter', 'Top');
INSERT INTO Champions(ChampionName, Tier, Archetype, RoleName)
VALUES ('Pantheon', '1',
           'Fighter', 'Jungle');
INSERT INTO Champions(ChampionName, Tier, Archetype, RoleName)
VALUES ('Azir', '1', 'Mage',
           'Mid');
INSERT INTO Champions(ChampionName, Tier, Archetype, RoleName)
VALUES ('Ezreal', '2',
           'Marksman', 'ADC');
INSERT INTO Champions(ChampionName, Tier, Archetype, RoleName)
VALUES ('Alistar', 'OP',
           'Tank', 'Support');
INSERT INTO Champions(ChampionName, Tier, Archetype, RoleName)
VALUES ('Kai''Sa', '1', 
           'Marksman', 'ADC');


INSERT INTO Stats(ChampionName, Winrate, Pickrate, Banrate)
VALUES ('Alistar', 54.69,
           19.55, 4.20);
INSERT INTO Stats(ChampionName, Winrate, Pickrate, Banrate)
VALUES ('Ezreal', 47.14,
           11.72, 2.50);
INSERT INTO Stats(ChampionName, Winrate, Pickrate, Banrate)
VALUES ('Azir', 52.23,
           10.44, 2.38);
INSERT INTO Stats(ChampionName, Winrate, Pickrate, Banrate)
VALUES ('Pantheon', 52.04,
           16.51, 19.58);
INSERT INTO Stats(ChampionName, Winrate, Pickrate, Banrate)
VALUES ('Ambessa', 50.94,
           13.67, 5.88);



INSERT INTO Build (ChampionName, SkillOrder, PatchID)
VALUES ('Ambessa', 'QEW', 25.14);
INSERT INTO Build (ChampionName, SkillOrder, PatchID)
VALUES ('Alistar', 'QWE', 25.13);
INSERT INTO Build (ChampionName, SkillOrder, PatchID)
VALUES ('Ezreal', 'QEW', 25.12);
INSERT INTO Build (ChampionName, SkillOrder, PatchID)
VALUES ('Azir', 'WQE', 25.11);
INSERT INTO Build (ChampionName, SkillOrder, PatchID)
VALUES ('Pantheon', 'QWE', 25.10);


INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Ambessa', 'Gwen', 25.14, 46.15, '39');
INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Pantheon', 'Trundle', 25.14, 51.35, '37');
INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Azir', 'Taliyah', 25.14, 42.55, '47');
INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Kai''Sa', 'Lucian', 25.13, 43.33, '30');
INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Alistar', 'Braum', 25.13, 39.39, '33');

INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Ambessa', 'Renekton', 25.13, 47.22, '36');
INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Ambessa', 'Jayce', 25.12, 46.15, '33');
INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Ambessa', 'jax', 25.11, 45.25, '35');
INSERT INTO Matchup(ChampionName, Counter_Champion, PatchID, MatchupWinrate,
                    MatchesPlayed)
VALUES ('Ambessa', 'Aurora', 25.10, 48.12, '38');







INSERT INTO BuildUsesItem (ChampionName, SkillOrder, ItemName)
VALUES ('Ambessa',
           'QEW', 'Heartsteel');
INSERT INTO BuildUsesItem (ChampionName, SkillOrder, ItemName)
VALUES ('Alistar',
            'QWE', 'Redemption');
INSERT INTO BuildUsesItem (ChampionName, SkillOrder, ItemName)
VALUES ('Ezreal',
           'QEW', 'Eclipse');
INSERT INTO BuildUsesItem (ChampionName, SkillOrder, ItemName)
VALUES ('Azir',
           'WQE', 'Zhonya''s Hourglass');
INSERT INTO BuildUsesItem (ChampionName, SkillOrder, ItemName)
VALUES ('Pantheon', 'QWE', 'Infinity Edge');