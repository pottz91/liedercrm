<?php
include 'datenbank.php';
//include 'auth.php';
//session_start();
?>



    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />

    
    <div id="navigation">
        <button id="prev">Vorherige Woche</button>
        <button id="next">Nächste Woche</button>
    </div>
    <div id="year-display"></div>
    <div id="calendar"></div>

    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new tui.Calendar(calendarEl, {
                defaultView: 'month', 
                isReadOnly: true,
                timezone: {},
                taskView: true, 
                scheduleView: ['time'], 
                week: { 
                    // Ändere workweek auf false, um eine 7-Tage-Woche zu haben
                    workweek: false,
                    showTimezoneCollapseButton: true, // Hinzugefügt, um die Zeitzonen-Schaltfläche anzuzeigen
                    daynames: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'], // Hinzugefügt, um das Jahr anzuzeigen
                }, 
                month: { 
                    // Ändere workweek auf false, um eine 7-Tage-Woche zu haben
                    workweek: false,
                    visibleWeeksCount: 6, // Hinzugefügt, um 6 Wochen anzuzeigen (kann angepasst werden)
                    showTitle: true, // Hinzugefügt, um die Monatsangabe anzuzeigen
     
                }, 
                useCreationPopup: false, 
                useDetailPopup: true,
                template: {
                    milestoneTitle: function() {
                        return '';
                    },
                    milestone: function() {
                        return '';
                    },
                    taskTitle: function() {
                        return '';
                    },
                    task: function() {
                        return '';
                    },
                    allday: function(schedule) {
                        return schedule.title;
                    },
                    time: function(schedule) {
                        return schedule.title;
                    }
                },
            });
            
            // Schaltflächen zur Navigation hinzufügen
            document.getElementById('prev').addEventListener('click', function() {
                calendar.prev();
            });
            document.getElementById('next').addEventListener('click', function() {
                calendar.next();
            });

            <?php
            include 'datenbank.php';

            $sql1 = "SELECT name, hinzugefuegt_am FROM lieder";
            $sql2 = "SELECT lieder.name, lieder_datum.datum 
                     FROM lieder 
                     INNER JOIN lieder_datum ON lieder.id = lieder_datum.lied_id";

            $result1 = $conn->query($sql1);
            $result2 = $conn->query($sql2);

            $events = array();

            if ($result1 && $result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    $startDatum = date('Y-m-d', strtotime($row['hinzugefuegt_am'])) . "T23:59:59";
                    $endDatum = date('Y-m-d', strtotime($row['hinzugefuegt_am'] . ' +0 day')) . "T23:59:59";
                    $event = array(
                        'id' => uniqid(),
                        'calendarId' => '1',
                        'title' => $row["name"],
                        'isAllDay' => true,
                        'start' => $startDatum,
                        'end' => $endDatum,
                    );
                    array_push($events, $event);
                }
            }

            if ($result2 && $result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    $startDatum = date('Y-m-d', strtotime($row['datum'])) . "T23:59:59";
                    $endDatum = date('Y-m-d', strtotime($row['datum'] . ' +0 day')) . "T23:59:59";
                    $event = array(
                        'id' => uniqid(),
                        'calendarId' => '1',
                        'title' => $row["name"],
                        'isAllDay' => true,
                        'start' => $startDatum,
                        'end' => $endDatum,
                    );
                    array_push($events, $event);
                }
            }



            $conn->close();

            echo "var events = ";
            echo json_encode($events);
            echo ";";
            echo "calendar.createEvents(events);";
            ?>
        });
    </script>


