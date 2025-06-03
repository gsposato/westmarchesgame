<?php

use common\models\Game;
use common\models\GameEvent;
use common\models\GamePollSlot;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Games';
$this->params['breadcrumbs'][] = $this->title;
$campaignId = $_GET['campaignId'];
$create = 'create?campaignId=' . $campaignId;
$roundup = 'roundup?campaignId=' . $campaignId;
//echo "<pre>";var_dump($games);echo "</pre>";die;
?>
<style>
    .calendar-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .day-cell {
      width: 100px;
      height: 100px;
      vertical-align: top;
      padding: 6px;
      font-size: 0.85rem;
    }

    .today {
      background-color: #0d6efd;
      color: white;
      border-radius: 50%;
      padding: 4px 8px;
      display: inline-block;
      font-weight: bold;
    }

    .event-badge {
      display: block;
      font-size: 0.7rem;
      margin-top: 4px;
      text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;
    }

    .day-content {
      display: flex;
      flex-direction: column;
      height: 100%;
      overflow: hidden;
    }
    .rainbow-text {
        background: linear-gradient(90deg, red, orange, yellow, green, blue, indigo, violet);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: bold;
    }
  </style>
<div class="calendar">
    <div class="calendar-controls">
      <button class="btn btn-outline-primary" id="prevBtn">&larr; Previous Month</button>
      <h2 class="mb-0" id="monthYear"><span id="monthText"></span> <span id="yearText"></span></h2>
      <button class="btn btn-outline-primary" id="nextBtn">Next Month&rarr;</button>
    </div>

    <table class="table table-bordered text-center">
      <thead class="table-light">
        <tr>
          <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th>
          <th>Thu</th><th>Fri</th><th>Sat</th>
        </tr>
      </thead>
      <tbody id="calendarBody"></tbody>
    </table>
  </div>

  <script>
    const events = {
    <?php foreach ($games as $game): ?>
        "<?= Game::event($game->id, "Y-m-d"); ?>": ["<?= $game->name; ?>"],
    <?php endforeach; ?>
    };

    let currentDate = new Date();

    function generateCalendar(date) {
      const currentMonth = date.getMonth();
      const currentYear = date.getFullYear();
      const today = new Date();

      const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ];

      // pride flag start
      const monthTextEl = document.getElementById("monthText");
      const yearTextEl = document.getElementById("yearText");
      monthTextEl.textContent = monthNames[currentMonth];
      yearTextEl.textContent = currentYear;
      if (monthNames[currentMonth] === "June") {
        monthTextEl.classList.add("rainbow-text");
      } else {
        monthTextEl.classList.remove("rainbow-text");
      }
      // pride flag end

      const firstDay = new Date(currentYear, currentMonth, 1).getDay();
      const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

      const calendarBody = document.getElementById("calendarBody");
      calendarBody.innerHTML = "";

      let dateCounter = 1;
      for (let i = 0; i < 6; i++) {
        const row = document.createElement("tr");

        for (let j = 0; j < 7; j++) {
          const cell = document.createElement("td");
          cell.className = "day-cell";

          if (i === 0 && j < firstDay) {
            cell.innerHTML = "";
          } else if (dateCounter > daysInMonth) {
            break;
          } else {
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(dateCounter).padStart(2, '0')}`;
            const cellContent = document.createElement("div");
            cellContent.className = "day-content";

            const dateSpan = document.createElement("div");
            if (
              dateCounter === today.getDate() &&
              currentMonth === today.getMonth() &&
              currentYear === today.getFullYear()
            ) {
              dateSpan.innerHTML = `<span class="today">${dateCounter}</span>`;
            } else {
              dateSpan.textContent = dateCounter;
            }
            cellContent.appendChild(dateSpan);

            if (events[dateStr]) {
              events[dateStr].forEach(event => {
                const badge = document.createElement("span");
                badge.className = "badge bg-secondary event-badge";
                badge.textContent = event;
                cellContent.appendChild(badge);
              });
            }

            cell.appendChild(cellContent);
            dateCounter++;
          }

          row.appendChild(cell);
        }

        calendarBody.appendChild(row);
        if (dateCounter > daysInMonth) break;
      }
    }

    document.getElementById("prevBtn").addEventListener("click", () => {
      currentDate.setMonth(currentDate.getMonth() - 1);
      generateCalendar(currentDate);
    });

    document.getElementById("nextBtn").addEventListener("click", () => {
      currentDate.setMonth(currentDate.getMonth() + 1);
      generateCalendar(currentDate);
    });

    generateCalendar(currentDate);
  </script>
