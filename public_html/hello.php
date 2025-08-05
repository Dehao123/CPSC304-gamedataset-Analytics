<html>
	<p>If PHP is working, you will see "Hello World" below, followed by some PHP version information:</p><hr />
	<?php
	echo "Hello world";
	phpinfo();  // Print PHP version and config info
	?>


   <!-- Create Champions Table -->
   <h2>All Champions</h2>
    <table id="championTable" border="1">
        <thead>
            <tr>
                <th>ChampionName</th>
                <th>Archetype</th>
                <th>Tier</th>
                <th>RoleName</th>   
            </tr>
        </thead>
        <tbody></tbody>
    </table>


    <br><hr><br>
    <!-- Insert Champion --> 
    <h2>Insert New Champion</h2> 
    <h3>INSERT Operation</h3>
    <form id="insertChampionForm"> 
        ChampionName: <input type="text" id="championName" required><br><br> 
        Archetype: <input type="text" id="archetype" required><br><br>
        Tier: <input type="text" id="tier" required><br><br>
        RoleName: <input type="text" id="roleName" required><br><br>
        <button type="submit">Insert Champion</button>
    </form>
    <div id="insertChampionMsg"></div>

    <br><hr><br>
</html>
