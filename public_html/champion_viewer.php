<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Champion Data Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .search-box {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .search-box input, .search-box select {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-box button {
            padding: 8px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-box button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏆 League of Legends Champion Database</h1>
        
        <div class="search-box">
            <form method="POST">
                <input type="text" name="champion_name" placeholder="Champion Name" value="<?php echo htmlspecialchars($_POST['champion_name'] ?? ''); ?>">
                <select name="role">
                    <option value="">All Roles</option>
                    <option value="Top" <?php echo ($_POST['role'] ?? '') === 'Top' ? 'selected' : ''; ?>>Top</option>
                    <option value="Jungle" <?php echo ($_POST['role'] ?? '') === 'Jungle' ? 'selected' : ''; ?>>Jungle</option>
                    <option value="Mid" <?php echo ($_POST['role'] ?? '') === 'Mid' ? 'selected' : ''; ?>>Mid</option>
                    <option value="ADC" <?php echo ($_POST['role'] ?? '') === 'ADC' ? 'selected' : ''; ?>>ADC</option>
                    <option value="Support" <?php echo ($_POST['role'] ?? '') === 'Support' ? 'selected' : ''; ?>>Support</option>
                </select>
                <select name="tier">
                    <option value="">All Tiers</option>
                    <option value="S" <?php echo ($_POST['tier'] ?? '') === 'S' ? 'selected' : ''; ?>>S Tier</option>
                    <option value="A" <?php echo ($_POST['tier'] ?? '') === 'A' ? 'selected' : ''; ?>>A Tier</option>
                    <option value="B" <?php echo ($_POST['tier'] ?? '') === 'B' ? 'selected' : ''; ?>>B Tier</option>
                    <option value="C" <?php echo ($_POST['tier'] ?? '') === 'C' ? 'selected' : ''; ?>>C Tier</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <?php
        // Database connection
        $config["dbuser"] = "ora_njiang05";
        $config["dbpassword"] = "a95232674";
        $config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
        
        function connectToDB() {
            global $config;
            $conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);
            if (!$conn) {
                $e = oci_error();
                echo "<div class='error'>Database connection failed: " . $e['message'] . "</div>";
                return false;
            }
            return $conn;
        }

        function getChampions($conn, $champion_name = '', $role = '', $tier = '') {
            $sql = "SELECT c.ChampionName, c.Archetype, c.Tier, c.RoleName, 
                           s.Winrate, s.Pickrate, s.Banrate
                    FROM Champions c
                    LEFT JOIN Stats s ON c.ChampionName = s.ChampionName
                    WHERE 1=1";
            
            $params = array();
            
            if (!empty($champion_name)) {
                $sql .= " AND UPPER(c.ChampionName) LIKE UPPER(:champion_name)";
                $params[':champion_name'] = '%' . $champion_name . '%';
            }
            
            if (!empty($role)) {
                $sql .= " AND c.RoleName = :role";
                $params[':role'] = $role;
            }
            
            if (!empty($tier)) {
                $sql .= " AND c.Tier = :tier";
                $params[':tier'] = $tier;
            }
            
            $sql .= " ORDER BY s.Winrate DESC NULLS LAST, c.ChampionName";
            
            $stmt = oci_parse($conn, $sql);
            
            foreach ($params as $key => $value) {
                oci_bind_by_name($stmt, $key, $value);
            }
            
            if (oci_execute($stmt)) {
                return $stmt;
            } else {
                return false;
            }
        }

        function getOverallStats($conn) {
            $sql = "SELECT 
                        COUNT(DISTINCT c.ChampionName) as total_champions,
                        AVG(s.Winrate) as avg_winrate,
                        AVG(s.Pickrate) as avg_pickrate,
                        AVG(s.Banrate) as avg_banrate
                    FROM Champions c
                    LEFT JOIN Stats s ON c.ChampionName = s.ChampionName";
            
            $stmt = oci_parse($conn, $sql);
            if (oci_execute($stmt)) {
                return oci_fetch_assoc($stmt);
            }
            return false;
        }

        // Main execution
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conn = connectToDB();
            if ($conn) {
                $champion_name = $_POST['champion_name'] ?? '';
                $role = $_POST['role'] ?? '';
                $tier = $_POST['tier'] ?? '';
                
                $champions = getChampions($conn, $champion_name, $role, $tier);
                $overall_stats = getOverallStats($conn);
                
                if ($overall_stats) {
                    echo "<div class='stats'>";
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['TOTAL_CHAMPIONS']) . "</div>";
                    echo "<div>Total Champions</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['AVG_WINRATE'], 1) . "%</div>";
                    echo "<div>Average Win Rate</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['AVG_PICKRATE'], 1) . "%</div>";
                    echo "<div>Average Pick Rate</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['AVG_BANRATE'], 1) . "%</div>";
                    echo "<div>Average Ban Rate</div>";
                    echo "</div>";
                    echo "</div>";
                }
                
                if ($champions) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Champion</th>";
                    echo "<th>Role</th>";
                    echo "<th>Tier</th>";
                    echo "<th>Win Rate</th>";
                    echo "<th>Pick Rate</th>";
                    echo "<th>Ban Rate</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    
                    while ($row = oci_fetch_assoc($champions)) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['CHAMPIONNAME']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['ROLENAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TIER']) . "</td>";
                        echo "<td>" . number_format($row['WINRATE'], 1) . "%</td>";
                        echo "<td>" . number_format($row['PICKRATE'], 1) . "%</td>";
                        echo "<td>" . number_format($row['BANRATE'], 1) . "%</td>";
                        echo "</tr>";
                    }
                    
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No champions found matching your criteria.</p>";
                }
                
                oci_close($conn);
            }
        } else {
            // Show welcome message
            echo "<p>Use the search form above to explore champion data from the database.</p>";
        }
        ?>
    </div>
</body>
</html> 