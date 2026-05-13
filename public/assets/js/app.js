document.addEventListener("DOMContentLoaded", () => {
  // ── Dynamic ingredient rows ──────────────────
  const addIngBtn = document.getElementById("add-ingredient");
  const ingList = document.getElementById("ingredient-list");
  if (addIngBtn && ingList) {
    addIngBtn.addEventListener("click", () => {
      const row = document.createElement("div");
      row.className = "ingredient-row";
      row.innerHTML = `
        <input type="text"   name="ing_name[]" placeholder="Ingredient name" required>
        <input type="number" name="ing_qty[]"  placeholder="Qty" step="0.01" min="0" value="1">
        <input type="text"   name="ing_unit[]" placeholder="Unit (g, cup…)">
        <button type="button" class="remove-btn" title="Remove">
          <span class="material-symbols-outlined">remove</span>
        </button>`;
      ingList.appendChild(row);
      row
        .querySelector(".remove-btn")
        .addEventListener("click", () => row.remove());
    });
    ingList.querySelectorAll(".remove-btn").forEach((btn) => {
      btn.addEventListener("click", () =>
        btn.closest(".ingredient-row").remove(),
      );
    });
  }

  // ── Dynamic direction rows ───────────────────
  const addDirBtn = document.getElementById("add-direction");
  const dirList = document.getElementById("direction-list");
  if (addDirBtn && dirList) {
    const updateSteps = () => {
      dirList.querySelectorAll(".step-badge").forEach((badge, i) => {
        badge.textContent = String(i + 1).padStart(2, "0");
      });
    };
    addDirBtn.addEventListener("click", () => {
      const count = dirList.querySelectorAll(".direction-row").length + 1;
      const row = document.createElement("div");
      row.className = "direction-row";
      row.innerHTML = `
        <div class="step-badge">${String(count).padStart(2, "0")}</div>
        <textarea name="direction[]" rows="3" placeholder="Describe this step…" required></textarea>
        <button type="button" class="remove-btn" title="Remove">
          <span class="material-symbols-outlined">remove</span>
        </button>`;
      dirList.appendChild(row);
      row.querySelector(".remove-btn").addEventListener("click", () => {
        row.remove();
        updateSteps();
      });
    });
    dirList.querySelectorAll(".remove-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        btn.closest(".direction-row").remove();
        updateSteps();
      });
    });
  }

  // ── Grocery list ─────────────────────────────
  const groceryItems = document.querySelectorAll(".grocery-item");

  if (groceryItems.length > 0) {
    // Check toggle on item click
    groceryItems.forEach((item) => {
      item.addEventListener("click", () => item.classList.toggle("checked"));
    });

    // Check all
    document
      .getElementById("check-all-grocery")
      ?.addEventListener("click", () => {
        groceryItems.forEach((i) => i.classList.add("checked"));
      });

    // Clear all
    document.getElementById("clear-grocery")?.addEventListener("click", () => {
      groceryItems.forEach((i) => i.classList.remove("checked"));
    });

    // ── Serving scaler ───────────────────────
    const scalerUp = document.getElementById("scaler-up");
    const scalerDown = document.getElementById("scaler-down");
    const scalerDisplay = document.getElementById("scaler-value");
    const presets = document.querySelectorAll(".scaler-preset");

    if (scalerUp && scalerDown && scalerDisplay) {
      let scale = 1;
      const MIN = 0.25;
      const MAX = 20;

      function formatQty(val) {
        if (val === 0) return "";
        const fractions = [
          [1 / 8, "⅛"],
          [1 / 4, "¼"],
          [1 / 3, "⅓"],
          [3 / 8, "⅜"],
          [1 / 2, "½"],
          [5 / 8, "⅝"],
          [2 / 3, "⅔"],
          [3 / 4, "¾"],
          [7 / 8, "⅞"],
        ];
        const whole = Math.floor(val);
        const remainder = val - whole;
        if (remainder < 0.01) return whole === 0 ? "" : String(whole);
        for (const [frac, sym] of fractions) {
          if (Math.abs(remainder - frac) < 0.04) {
            return whole > 0 ? `${whole} ${sym}` : sym;
          }
        }
        return parseFloat(val.toFixed(2)).toString();
      }

      function updateQtys() {
        groceryItems.forEach((item) => {
          const base = parseFloat(item.dataset.base) || 0;
          const qtyEl = item.querySelector(".qty-value");
          if (!qtyEl) return;
          const newText = formatQty(base * scale);
          const changed = qtyEl.textContent !== newText;
          qtyEl.textContent = newText;
          if (changed) {
            item.querySelector(".grocery-qty")?.classList.add("qty-changed");
            setTimeout(
              () =>
                item
                  .querySelector(".grocery-qty")
                  ?.classList.remove("qty-changed"),
              300,
            );
          }
        });
      }

      function setScale(val) {
        scale = Math.round(Math.max(MIN, Math.min(MAX, val)) * 4) / 4;
        scalerDisplay.textContent =
          scale % 1 === 0 ? scale : scale.toFixed(2).replace(/\.?0+$/, "");
        presets.forEach((p) =>
          p.classList.toggle("active", parseFloat(p.dataset.value) === scale),
        );
        updateQtys();
      }

      scalerUp.addEventListener("click", () =>
        setScale(scale + (scale < 2 ? 0.25 : 1)),
      );
      scalerDown.addEventListener("click", () =>
        setScale(scale - (scale <= 2 ? 0.25 : 1)),
      );
      presets.forEach((p) =>
        p.addEventListener("click", () =>
          setScale(parseFloat(p.dataset.value)),
        ),
      );
    }
  }
});
