<?php
session_start();

// CORRECCIÓN: Usamos la misma variable que definimos en login.php
if (!isset($_SESSION["user_login"])) {
    header("Location: index.html");
    exit;
}

require "./php/bd.php"; 
$name = "Spelling";
$currentSubjectID = 1; 

// Obtener el nombre del usuario logueado para usarlo si quieres
$usuarioLogueado = $_SESSION["user_login"];

$studentsQuery = $conn->query("SELECT studentsID, name FROM students ORDER BY name ASC");
$students = $studentsQuery->fetch_all(MYSQLI_ASSOC);

$activitiesQuery = $conn->query("SELECT activityID, activityName FROM activities WHERE subjectID = $currentSubjectID ORDER BY activityID ASC");
$activities = $activitiesQuery->fetch_all(MYSQLI_ASSOC);

$gradesQuery = $conn->query("
    SELECT g.studentsID, g.activityID, g.grade 
    FROM grades g
    JOIN activities a ON g.activityID = a.activityID
    WHERE a.subjectID = $currentSubjectID
");

$grades = [];
while ($g = $gradesQuery->fetch_assoc()) {
    $grades[$g['studentsID']][$g['activityID']] = $g['grade'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spelling Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/mediaqueries.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <section>
        <header>
            <?php include "./layouts/header.php" ?>
        </header>

        <div id="mnBody">
            <div id="selectSection">
                <?php include "./layouts/icons.php" ?> 
            </div>

            <div id="table">
                
                <!-- Mensaje de Bienvenida Opcional -->
                <div style="width: 100%; text-align: right; padding: 10px; color: #800020; font-weight: bold;">
                    <i class="fa-solid fa-user-check"></i> Profesor: <?= htmlspecialchars($usuarioLogueado) ?>
                </div>

                <div id="schlInputs">
                    <!-- FORMULARIO 1: AGREGAR -->
                    <form action="./php/saveActivity.php" method="POST">
                        <fieldset>
                            <input type="hidden" name="subjectID" value="<?= $currentSubjectID ?>">
                            <label for="activity">Nueva Actividad:</label>
                            <input type="text" id="activity" name="activityName" placeholder="Nombre Actividad" required autocomplete="off">
                            <button id="schlSend" type="submit">Agregar</button>
                        </fieldset>
                    </form>

                    <!-- SEPARADOR VISUAL -->
                    <div style="margin: 10px 0; border-bottom: 1px solid #ddd;"></div>

                    <!-- FORMULARIO 2: BORRAR -->
                    <form action="./php/deleteActivity.php" method="POST">
                        <fieldset>
                            <label for="delAct">Borrar Actividad (ID):</label>
                            <input type="number" id="delAct" name="deleteActivityID" placeholder="ID ej: 5" required>
                            <button id="schlSend" type="submit" style="background-color: #c0392b;">Borrar</button>
                        </fieldset>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <?php foreach ($activities as $act): ?>
                                    <th>
                                        <?= htmlspecialchars($act['activityName']) ?>
                                        <br>
                                        <span style="font-size: 0.8em; opacity: 0.8; font-weight: normal;">(ID: <?= $act['activityID'] ?>)</span>
                                    </th>
                                <?php endforeach; ?>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $stu): ?>
                                <?php 
                                    $suma = 0;
                                    $cantidad = 0;
                                ?>
                                <tr class="student-row">
                                    <td style="text-align: left; font-weight: bold; padding-left: 15px;">
                                        <?= htmlspecialchars($stu['name']) ?>
                                    </td>
                                    
                                    <?php foreach ($activities as $act): ?>
                                        <?php 
                                            $nota = $grades[$stu['studentsID']][$act['activityID']] ?? null;
                                            
                                            if ($nota !== null && $nota !== "") {
                                                $suma += floatval($nota);
                                                $cantidad++;
                                            }
                                        ?>
                                        <td class="editable"
                                            data-student="<?= $stu['studentsID'] ?>"
                                            data-activity="<?= $act['activityID'] ?>">
                                            <?= $nota ?? "-" ?>
                                        </td>
                                    <?php endforeach; ?>

                                    <td class="average-cell" style="font-weight: bold;">
                                        <?php 
                                            if ($cantidad > 0) {
                                                echo number_format($suma / $cantidad, 1); 
                                            } else {
                                                echo "-";
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'duplicate') {
            alert("⚠️ ¡Esa actividad ya existe! Por favor usa otro nombre.");
            window.history.replaceState(null, null, window.location.pathname);
        }
    };

    document.querySelectorAll(".editable").forEach(cell => {
        cell.addEventListener("click", function () {
            if (this.querySelector("input")) return;

            let currentVal = this.innerText.trim();
            if(currentVal === "-") currentVal = "";

            let input = document.createElement("input");
            input.type = "number";
            input.min = 0;
            input.max = 100;
            input.value = currentVal;
            
            input.style.width = "100%";
            input.style.height = "100%";
            input.style.textAlign = "center";
            input.style.border = "none";
            input.style.outline = "2px solid #800020"; 
            input.style.background = "#fff";
            input.style.fontSize = "inherit";
            input.style.fontWeight = "bold";
            
            this.innerHTML = "";
            this.appendChild(input);
            input.focus();

            input.addEventListener("blur", () => {
                let val = input.value;
                if (val !== "") {
                    let num = parseFloat(val);
                    if (num < 0) val = "0";
                    if (num > 100) val = "100";
                }
                saveData(this, val);
            });

            input.addEventListener("keydown", (e) => {
                if (e.key === "Enter") input.blur();
            });
        });
    });

    function saveData(cell, value) {
        cell.innerHTML = value === "" ? "-" : value;
        updateRowAverage(cell.parentElement);

        let studentID = cell.dataset.student;
        let activityID = cell.dataset.activity;

        fetch("./php/saveGrade.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `studentID=${studentID}&activityID=${activityID}&grade=${value}`
        })
        .then(res => res.text())
        .then(data => { console.log("Guardado exitoso"); });
    }

    function updateRowAverage(row) {
        let sum = 0;
        let count = 0;
        let cells = row.querySelectorAll(".editable");
        
        cells.forEach(c => {
            let val = parseFloat(c.innerText);
            if (!isNaN(val)) {
                sum += val;
                count++;
            }
        });
        let avgCell = row.querySelector(".average-cell");
        if (count > 0) {
            avgCell.innerText = (sum / count).toFixed(1);
        } else {
            avgCell.innerText = "-";
        }
    }
    </script>
</body>
</html>