<?php
$servername = "csci3287.cse.ucdenver.edu";
$username = "larfie";
$password = "i4/tzSHdIgVC";
$dbname = "larfie_DB";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query 1: Champions in the last 3 years
    $query1 = "SELECT F.Fname, F.Lname, F.WeightClass
    FROM CHAMPIONS C
    JOIN FIGHTERS F ON C.FighterID = F.FighterID
    WHERE C.Date >= DATE_SUB(CURDATE(), INTERVAL 3 YEAR)
    ORDER BY F.WeightClass, YEAR(C.Date) DESC;";
    
    $stmt1 = $pdo->query($query1);
    $results1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // table for Query 1
    echo "<h2>Champions in the last 3 years</h2>";
    echo "<table border='1'>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Weight Class</th>
            </tr>";
    foreach ($results1 as $row) {
        echo "<tr>";
        echo "<td>" . $row['Fname'] . "</td>";
        echo "<td>" . $row['Lname'] . "</td>";
        echo "<td>" . $row['WeightClass'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Query 2: Summary of Fight Styles and Wins
    $query2 = "SELECT
                    FS.FightingStyle,
                    FIGHTINGSTYLES.Summary,
                    SUM(F.Wins) AS TotalWins,
                    SUM(F.NumOfTakedowns) AS TotalTakedowns
                FROM
                    FIGHTERSTYLES FS
                JOIN
                    FIGHTERS F ON FS.FighterID = F.FighterID
                JOIN
                    FIGHTINGSTYLES ON FS.FightingStyle = FIGHTINGSTYLES.Name
                GROUP BY
                    FS.FightingStyle
                ORDER BY
                    TotalWins DESC;";
    
    $stmt2 = $pdo->query($query2);
    $results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Summary of Fight Styles and Wins</h2>";
    echo "<table border='1'>
            <tr>
                <th>Fighting Style</th>
                <th>Summary</th>
                <th>Total Wins</th>
                <th>Total Takedowns</th>
            </tr>";
    foreach ($results2 as $row) {
        echo "<tr>";
        echo "<td>" . $row['FightingStyle'] . "</td>";
        echo "<td>" . $row['Summary'] . "</td>";
        echo "<td>" . $row['TotalWins'] . "</td>";
        echo "<td>" . $row['TotalTakedowns'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Query 3: Active Fighters with Fighting Styles, ordered by Weight Class and Last Name
    $query3 = "SELECT
                    F.Country,
                    F.WeightClass,
                    F.FighterID,
                    F.Lname,
                    FS.FightingStyle
                FROM
                    FIGHTERS F
                INNER JOIN
                    FIGHTERSTYLES FS ON F.FighterID = FS.FighterID
                WHERE
                    F.Active = true
                ORDER BY
                    F.WeightClass, F.Lname;";
    
    $stmt3 = $pdo->query($query3);
    $results3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Active Fighters with Fighting Styles (Ordered)</h2>";
    echo "<table border='1'>
            <tr>
                <th>Country</th>
                <th>Weight Class</th>
                <th>Fighter ID</th>
                <th>Last Name</th>
                <th>Fighting Style</th>
            </tr>";
    foreach ($results3 as $row) {
        echo "<tr>";
        echo "<td>" . $row['Country'] . "</td>";
        echo "<td>" . $row['WeightClass'] . "</td>";
        echo "<td>" . $row['FighterID'] . "</td>";
        echo "<td>" . $row['Lname'] . "</td>";
        echo "<td>" . $row['FightingStyle'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Query 4: Matches in 2023
    $query4 = "SELECT MatchID, Date, Victory, NumOfRounds 
                FROM MATCHES 
                WHERE YEAR(Date) = 2023";
    
    $stmt4 = $pdo->query($query4);
    $results4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    // Output HTML table for Query 4
    echo "<h2>Matches in 2023</h2>";
    echo "<table border='1'>
            <tr>
                <th>Match ID</th>
                <th>Date</th>
                <th>Victory</th>
                <th>Number of Rounds</th>
            </tr>";
    foreach ($results4 as $row) {
        echo "<tr>";
        echo "<td>" . $row['MatchID'] . "</td>";
        echo "<td>" . $row['Date'] . "</td>";
        echo "<td>" . $row['Victory'] . "</td>";
        echo "<td>" . $row['NumOfRounds'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Query 5: Missed Weight
    $query5 = "SELECT FighterID, WghtMissed, Fine, CheckInDate
                FROM MISSED_WEIGHT;";
    
    $stmt5 = $pdo->query($query5);
    $results5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Missed Weight</h2>";
    echo "<table border='1'>
            <tr>
                <th>Fighter ID</th>
                <th>Weight Missed</th>
                <th>Fine</th>
                <th>Check-In Date</th>
            </tr>";
    foreach ($results5 as $row) {
        echo "<tr>";
        echo "<td>" . $row['FighterID'] . "</td>";
        echo "<td>" . $row['WghtMissed'] . "</td>";
        echo "<td>" . $row['Fine'] . "</td>";
        echo "<td>" . $row['CheckInDate'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Query 6: Matches with Fighter Names
    $query6 = "SELECT M.Date, M.Time, M.Location, F1.Fname AS Fighter1FirstName, F1.Lname AS Fighter1LastName, F2.Fname AS Fighter2FirstName, F2.Lname AS Fighter2LastName
                FROM MATCHES M
                LEFT JOIN FIGHTERS F1 ON M.Fighter1ID = F1.FighterID
                LEFT JOIN FIGHTERS F2 ON M.Fighter2ID = F2.FighterID
                WHERE M.Date > CURDATE();";
    
    $stmt6 = $pdo->query($query6);
    $results6 = $stmt6->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Matches with Fighter Names</h2>";
    echo "<table border='1'>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Fighter 1 First Name</th>
                <th>Fighter 1 Last Name</th>
                <th>Fighter 2 First Name</th>
                <th>Fighter 2 Last Name</th>
            </tr>";
    foreach ($results6 as $row) {
        echo "<tr>";
        echo "<td>" . $row['Date'] . "</td>";
        echo "<td>" . $row['Time'] . "</td>";
        echo "<td>" . $row['Location'] . "</td>";
        echo "<td>" . $row['Fighter1FirstName'] . "</td>";
        echo "<td>" . $row['Fighter1LastName'] . "</td>";
        echo "<td>" . $row['Fighter2FirstName'] . "</td>";
        echo "<td>" . $row['Fighter2LastName'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>