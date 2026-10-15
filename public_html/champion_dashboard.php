<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoL Champion Analytics Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #333;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .search-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .search-btn {
            background: linear-gradient(45deg, #4a90e2, #357abd);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #4a90e2;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .data-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }

        .champions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .champions-table th,
        .champions-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        .champions-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .champions-table tr:hover {
            background: #f8f9fa;
        }

        .winrate-high { color: #27ae60; font-weight: bold; }
        .winrate-medium { color: #f39c12; font-weight: bold; }
        .winrate-low { color: #e74c3c; font-weight: bold; }

        .tier-s { background: #ff6b6b; color: white; padding: 4px 8px; border-radius: 4px; }
        .tier-a { background: #4ecdc4; color: white; padding: 4px 8px; border-radius: 4px; }
        .tier-b { background: #45b7d1; color: white; padding: 4px 8px; border-radius: 4px; }
        .tier-c { background: #96ceb4; color: white; padding: 4px 8px; border-radius: 4px; }

        .loading {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }

        .error {
            background: #ff6b6b;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            
            .form-group {
                min-width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏆 LoL Champion Analytics</h1>
            <p>Data-driven insights for League of Legends players</p>
        </div>

        <div class="search-section">
            <h2 class="section-title">🔍 Search Champions</h2>
            <form method="POST" class="search-form">
                <div class="form-group">
                    <label for="champion_name">Champion Name</label>
                    <input type="text" id="champion_name" name="champion_name" placeholder="e.g., Yasuo, Jinx">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="">All Roles</option>
                        <option value="Top">Top</option>
                        <option value="Jungle">Jungle</option>
                        <option value="Mid">Mid</option>
                        <option value="ADC">ADC</option>
                        <option value="Support">Support</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tier">Tier</label>
                    <select id="tier" name="tier">
                        <option value="">All Tiers</option>
                        <option value="S">S Tier</option>
                        <option value="A">A Tier</option>
                        <option value="B">B Tier</option>
                        <option value="C">C Tier</option>
                    </select>
                </div>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        <?php
        // Database connection configuration
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

        function getChampionStats($conn, $champion_name = '', $role = '', $tier = '') {
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
                
                $champions = getChampionStats($conn, $champion_name, $role, $tier);
                $overall_stats = getOverallStats($conn);
                
                if ($overall_stats) {
                    echo "<div class='stats-grid'>";
                    echo "<div class='stat-card'>";
                    echo "<h3>Total Champions</h3>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['TOTAL_CHAMPIONS']) . "</div>";
                    echo "<div class='stat-label'>Available in database</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<h3>Average Win Rate</h3>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['AVG_WINRATE'], 1) . "%</div>";
                    echo "<div class='stat-label'>Across all champions</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<h3>Average Pick Rate</h3>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['AVG_PICKRATE'], 1) . "%</div>";
                    echo "<div class='stat-label'>Popularity metric</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<h3>Average Ban Rate</h3>";
                    echo "<div class='stat-number'>" . number_format($overall_stats['AVG_BANRATE'], 1) . "%</div>";
                    echo "<div class='stat-label'>Ban frequency</div>";
                    echo "</div>";
                    echo "</div>";
                }
                
                if ($champions) {
                    echo "<div class='data-section'>";
                    echo "<h2 class='section-title'>📊 Champion Statistics</h2>";
                    echo "<table class='champions-table'>";
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
                        $winrate_class = '';
                        if ($row['WINRATE'] > 52) $winrate_class = 'winrate-high';
                        elseif ($row['WINRATE'] > 48) $winrate_class = 'winrate-medium';
                        else $winrate_class = 'winrate-low';
                        
                        $tier_class = 'tier-' . strtolower($row['TIER']);
                        
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['CHAMPIONNAME']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['ROLENAME']) . "</td>";
                        echo "<td><span class='$tier_class'>" . htmlspecialchars($row['TIER']) . "</span></td>";
                        echo "<td class='$winrate_class'>" . number_format($row['WINRATE'], 1) . "%</td>";
                        echo "<td>" . number_format($row['PICKRATE'], 1) . "%</td>";
                        echo "<td>" . number_format($row['BANRATE'], 1) . "%</td>";
                        echo "</tr>";
                    }
                    
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<div class='data-section'>";
                    echo "<div class='loading'>No champions found matching your criteria.</div>";
                    echo "</div>";
                }
                
                oci_close($conn);
            }
        } else {
            // Show welcome message and sample data
            echo "<div class='stats-grid'>";
            echo "<div class='stat-card'>";
            echo "<h3>🏆 Welcome to LoL Analytics</h3>";
            echo "<div class='stat-number'>Search</div>";
            echo "<div class='stat-label'>Use the search form above to explore champion data</div>";
            echo "</div>";
            
            echo "<div class='stat-card'>";
            echo "<h3>📈 Data Available</h3>";
            echo "<div class='stat-number'>Win Rates</div>";
            echo "<div class='stat-label'>Performance statistics for all champions</div>";
            echo "</div>";
            
            echo "<div class='stat-card'>";
            echo "<h3>🎯 Pick & Ban</h3>";
            echo "<div class='stat-number'>Meta Data</div>";
            echo "<div class='stat-label'>Current meta analysis and trends</div>";
            echo "</div>";
            
            echo "<div class='stat-card'>";
            echo "<h3>⚔️ Role Analysis</h3>";
            echo "<div class='stat-number'>Position Data</div>";
            echo "<div class='stat-label'>Champion performance by role</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html> 