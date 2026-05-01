(function () {
  const _now = new Date();
  const today = _now.getFullYear() + '-' + String(_now.getMonth() + 1).padStart(2, '0') + '-' + String(_now.getDate()).padStart(2, '0');
  let dayData = null;

  function toMinutes(timeStr) {
    const [h, m] = timeStr.split(':').map(Number);
    return h * 60 + m;
  }

  function nowMinutes() {
    const n = new Date();
    return n.getHours() * 60 + n.getMinutes();
  }

  function renderItems(items) {
    for (let i = 0; i < 3; i++) {
      document.getElementById('item-' + i).textContent = items[i] || '';
    }
  }

  function renderGoal() {
    if (!dayData) return;

    const goals = dayData.goals || [];
    const now = nowMinutes();

    const content  = document.getElementById('goal-content');
    const doneEl   = document.getElementById('goal-done');
    const emptyEl  = document.getElementById('goal-empty');
    const textEl   = document.getElementById('goal-text');
    const deadEl   = document.getElementById('goal-deadline');
    const checkbox = document.getElementById('goal-checkbox');

    if (goals.length === 0) {
      content.style.display  = 'none';
      doneEl.style.display   = 'none';
      emptyEl.style.display  = 'block';
      return;
    }

    // 締切超過かつ未達成 → サーバーに achieved=true を送って自動スキップ
    const skips = [];
    goals.forEach((g, idx) => {
      if (!g.achieved && toMinutes(g.time) <= now) skips.push(idx);
    });

    if (skips.length > 0) {
      skips.forEach(idx => {
        dayData.goals[idx].achieved = true;
        fetch('api/toggle_goal.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ date: today, index: idx })
        });
      });
    }

    // 最初の未達成目標を探す
    const current = dayData.goals.findIndex(g => !g.achieved);

    if (current === -1) {
      content.style.display  = 'none';
      doneEl.style.display   = 'block';
      emptyEl.style.display  = 'none';
      return;
    }

    const goal = dayData.goals[current];
    const isPast = toMinutes(goal.time) <= now;

    content.style.display  = 'flex';
    doneEl.style.display   = 'none';
    emptyEl.style.display  = 'none';

    textEl.textContent   = goal.text;
    deadEl.textContent   = goal.time + ' まで';
    checkbox.checked     = false;
    checkbox.disabled    = isPast;
    textEl.classList.toggle('expired', isPast);

    checkbox.onclick = null;
    if (!isPast) {
      checkbox.onclick = function () {
        checkbox.disabled = true;
        dayData.goals[current].achieved = true;
        fetch('api/toggle_goal.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ date: today, index: current })
        }).then(() => renderGoal());
      };
    }
  }

  function load() {
    fetch('api/get_day.php?date=' + today)
      .then(r => r.json())
      .then(data => {
        dayData = data;
        renderItems(data.items || ['', '', '']);
        renderGoal();
      });
  }

  load();

  // 1分ごとに時刻チェック（自動進行）
  setInterval(renderGoal, 60 * 1000);
})();
