(function () {
  const dateInput   = document.getElementById('edit-date');
  const goalsList   = document.getElementById('goals-list');
  const btnAddGoal  = document.getElementById('btn-add-goal');
  const btnSave     = document.getElementById('btn-save');
  const saveMsg     = document.getElementById('save-msg');
  const historyList = document.getElementById('history-list');

  // 今日の日付をデフォルトに
  const _now = new Date();
  dateInput.value = _now.getFullYear() + '-' + String(_now.getMonth() + 1).padStart(2, '0') + '-' + String(_now.getDate()).padStart(2, '0');

  // 目標行を追加
  function addGoalRow(time, text, achieved) {
    const row = document.createElement('div');
    row.className = 'goal-row';

    const timeInput = document.createElement('input');
    timeInput.type = 'time';
    timeInput.className = 'goal-time';
    timeInput.value = time || '';

    const textInput = document.createElement('input');
    textInput.type = 'text';
    textInput.className = 'goal-text';
    textInput.placeholder = 'やること';
    textInput.value = text || '';

    const removeBtn = document.createElement('button');
    removeBtn.className = 'btn-remove';
    removeBtn.textContent = '×';
    removeBtn.onclick = () => row.remove();

    row.append(timeInput, textInput, removeBtn);
    goalsList.appendChild(row);
  }

  btnAddGoal.onclick = () => addGoalRow('', '', false);

  // フォームに日付データを読み込む
  function loadDate(date) {
    fetch('api/get_day.php?date=' + date)
      .then(r => r.json())
      .then(data => {
        const items = data.items || ['', '', ''];
        for (let i = 0; i < 3; i++) {
          document.getElementById('item-' + i).value = items[i] || '';
        }

        goalsList.innerHTML = '';
        (data.goals || []).forEach(g => addGoalRow(g.time, g.text, g.achieved));
      });
  }

  dateInput.addEventListener('change', () => loadDate(dateInput.value));
  loadDate(dateInput.value);

  // 保存
  btnSave.onclick = function () {
    const date = dateInput.value;
    if (!date) { alert('日付を選択してください'); return; }

    const items = [0, 1, 2].map(i => document.getElementById('item-' + i).value.trim());

    const goals = [];
    goalsList.querySelectorAll('.goal-row').forEach(row => {
      const time = row.querySelector('.goal-time').value;
      const text = row.querySelector('.goal-text').value.trim();
      if (time && text) goals.push({ time, text, achieved: false });
    });

    fetch('api/save_day.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ date, items, goals })
    })
      .then(r => r.json())
      .then(res => {
        if (res.ok) {
          saveMsg.textContent = '保存しました';
          setTimeout(() => (saveMsg.textContent = ''), 2000);
          loadHistory();
        }
      });
  };

  // 過去一覧
  function loadHistory() {
    fetch('api/get_all.php')
      .then(r => r.json())
      .then(all => {
        historyList.innerHTML = '';
        const dates = Object.keys(all);

        if (dates.length === 0) {
          historyList.innerHTML = '<p class="history-empty">まだ記録がありません</p>';
          return;
        }

        dates.forEach(date => {
          const day = all[date];
          const el  = document.createElement('div');
          el.className = 'history-day';

          const header = document.createElement('div');
          header.className = 'history-day-header';

          const dateEl = document.createElement('span');
          dateEl.className = 'history-date';
          dateEl.textContent = date;

          const editBtn = document.createElement('button');
          editBtn.className = 'btn-edit';
          editBtn.textContent = '編集';
          editBtn.onclick = () => {
            dateInput.value = date;
            loadDate(date);
            window.scrollTo({ top: 0, behavior: 'smooth' });
          };

          header.append(dateEl, editBtn);

          const itemsEl = document.createElement('div');
          itemsEl.className = 'history-items';
          (day.items || []).filter(t => t).forEach(t => {
            const span = document.createElement('span');
            span.className = 'history-item';
            span.textContent = t;
            itemsEl.appendChild(span);
          });

          const goalsEl = document.createElement('div');
          goalsEl.className = 'history-goals';
          (day.goals || []).forEach(g => {
            const row = document.createElement('div');
            row.className = 'history-goal-row' + (g.achieved ? ' achieved' : '');
            const check = g.achieved ? '<span class="goal-check">✓</span> ' : '○ ';
            row.innerHTML = check + g.time + '　' + g.text;
            goalsEl.appendChild(row);
          });

          el.append(header, itemsEl, goalsEl);
          historyList.appendChild(el);
        });
      });
  }

  loadHistory();
})();
