<?php
    // SQL-Statement zum Abrufen der Lieder aus der Datenbank mit deutschem Datums- und Uhrzeitformat
    $sql = "SELECT lieder.id, lieder.name, lieder.autor, lieder.ton, lieder.pdf_attachment, benutzer.benutzername, lieder.tags, COALESCE(DATE_FORMAT(lieder_datum.datum, '%d.%m.%Y'), DATE_FORMAT(lieder.hinzugefuegt_am, '%d.%m.%Y')) AS datum, COALESCE(abspielungen.gesamt_abspielungen, 0) AS gesamt_abspielungen
        FROM lieder
        LEFT JOIN benutzer ON lieder.benutzer_id = benutzer.id
        LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id
        LEFT JOIN (SELECT lieder_id, COUNT(*) AS gesamt_abspielungen FROM abspielungen GROUP BY lieder_id) AS abspielungen ON lieder.id = abspielungen.lieder_id";

            $result = $conn->query($sql);

            // Überprüfen, ob Zeilen in der Abfrageergebnismenge vorhanden sind
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $pdfPath = 'pdf/' . $row["pdf_attachment"];
                    $pdfButton = '';

                    // Überprüfen, ob ein PDF-Anhang vorhanden ist
                    if (!empty($row["pdf_attachment"])) {
                        $pdfButton = "<a href='$pdfPath' target='_blank' class='btn btn-sm btn-primary'>PDF</a>";
                    }
                    if (!empty($row["tags"])) {
                        // Tags als Badges generieren
                        $tags = explode(",", $row["tags"]);
                        $tagBadges = "";
                        foreach ($tags as $tag) {
                            $tagBadges .= "<span class='badge bg-secondary'>$tag</span> ";
                        }

                    } else {
                        $tagBadges = "";
                    }
                    echo "<tr>
                    <td>" . $row["name"] . "</td>
                    <td>" . $row["autor"] . "</td>
                    <td>" . $row["ton"] . "</td>
                    <td>$pdfButton</td>
                    <td>" . $row["benutzername"] . "</td>
                    <td>" . $tagBadges . "</td>
                    <td>
                    <button type='button' class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editModal' data-id='" . $row["id"] . "' data-name='" . $row["name"] . "' data-autor='" . $row["autor"] . "' data-ton='" . $row["ton"] . "' data-pdf='" . $row["pdf_attachment"] . "' data-tags='" . $row["tags"] . "'>Bearbeiten</button>
                    </td>
                    <td>" . $row["datum"] . "</td>
                    <td>" . $row["gesamt_abspielungen"] . "</td>
                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>Keine Lieder gefunden.</td></tr>";
                                    }
                                    ?>
