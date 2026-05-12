document.addEventListener("DOMContentLoaded", () => {
  // Dynamic ingredient rows
  const addIngBtn = document.getElementById("add-ingredient");
  const ingList = document.getElementById("ingredient-list");
  if (addIngBtn && ingList) {
    addIngBtn.addEventListener("click", () => {
      const idx = ingList.querySelectorAll(".ingredient-row").length;
      const row = document.createElement("div");
      row.className = "ingredient-row";
      row.innerHTML = `
        <input type="text"   name="ing_name[]"  placeholder="Ingredient name" required>
        <input type="number" name="ing_qty[]"   placeholder="Qty" step="0.01" min="0" value="1">
        <input type="text"   name="ing_unit[]"  placeholder="Unit (g, cup…)">
        <button type="button" class="remove-btn" title="Remove">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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

  //Dynamic direction rows
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
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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

  // Grocery checklist toggle
  document.querySelectorAll(".grocery-item").forEach((item) => {
    item.addEventListener("click", () => item.classList.toggle("checked"));
  });

  // Clear all grocery
  const clearBtn = document.getElementById("clear-grocery");
  if (clearBtn) {
    clearBtn.addEventListener("click", () => {
      document
        .querySelectorAll(".grocery-item")
        .forEach((i) => i.classList.remove("checked"));
    });
  }

  //  Check all grocery
  const checkAllBtn = document.getElementById("check-all-grocery");
  if (checkAllBtn) {
    checkAllBtn.addEventListener("click", () => {
      document
        .querySelectorAll(".grocery-item")
        .forEach((i) => i.classList.add("checked"));
    });
  }
});
