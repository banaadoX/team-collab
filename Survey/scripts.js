document.addEventListener("DOMContentLoaded", function () {
  // Set max date to today for all date and datetime-local fields
  const today = new Date().toISOString().split("T")[0];
  const nowLocal = new Date(new Date() - new Date().getTimezoneOffset() * 60000)
    .toISOString()
    .slice(0, 16);

  document.querySelectorAll('input[type="date"]').forEach((el) => {
    el.setAttribute("max", today);
  });

  document.querySelectorAll('input[type="datetime-local"]').forEach((el) => {
    el.setAttribute("max", nowLocal);
  });

  // Red glow validation on submit
  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      let valid = true;
      this.querySelectorAll("[required]").forEach((field) => {
        if (!field.value.trim()) {
          field.style.borderColor = "#ef4444";
          field.style.boxShadow = "0 0 0 3px rgba(239, 68, 68, 0.25)";
          valid = false;
        } else {
          field.style.borderColor = "";
          field.style.boxShadow = "";
        }
      });
      if (!valid) e.preventDefault();
    });
  });

  // Clear red glow on input
  document.querySelectorAll("input, select, textarea").forEach((field) => {
    field.addEventListener("input", function () {
      this.style.borderColor = "";
      this.style.boxShadow = "";
    });
    field.addEventListener("change", function () {
      this.style.borderColor = "";
      this.style.boxShadow = "";
    });
  });
});

function toggleMenu() {
  const isChecked = document.getElementById("consent-check").checked;
  document.getElementById("path-selection").style.display = isChecked
    ? "block"
    : "none";
}

function showPath(pathId) {
  ["landing-page", "path-a", "path-b", "path-c"].forEach((id) => {
    const el = document.getElementById(id);
    el.style.display = "none";
    el.classList.add("hidden");
  });
  const target = document.getElementById(pathId);
  target.classList.remove("hidden");
  target.style.display = "block";
  window.scrollTo(0, 0);
}

function goBack() {
  ["path-a", "path-b", "path-c"].forEach((id) => {
    const el = document.getElementById(id);
    el.style.display = "none";
    el.classList.add("hidden");
  });
  const landing = document.getElementById("landing-page");
  landing.classList.remove("hidden");
  landing.style.display = "block";
  window.scrollTo(0, 0);
}

function toggleBox(checkbox, id) {
  const target = document.getElementById(id);
  if (target) target.style.display = checkbox.checked ? "block" : "none";
}

function toggleBoxManual(show, id) {
  const target = document.getElementById(id);
  if (target) target.style.display = show ? "block" : "none";
}

function checkManualEntry(val) {
  const box = document.getElementById("manual-stall-box");
  const desc = document.getElementById("manual-desc");
  if (!box) return;
  if (val === "roaming" || val === "manual") {
    box.style.display = "block";
    if (desc) desc.setAttribute("required", "true");
  } else {
    box.style.display = "none";
    if (desc) desc.removeAttribute("required");
  }
}

function checkManualEntryPathB(val) {
  const box = document.getElementById("qb-manual-stall-box");
  if (!box) return;
  box.style.display = val === "roaming" || val === "manual" ? "block" : "none";
}

function checkManualEntryPathC(val) {
  const box = document.getElementById("qc-manual-stall-box");
  if (!box) return;
  box.style.display = val === "roaming" || val === "manual" ? "block" : "none";
}

function handleRequiredOther(val) {
  const otherInput = document.getElementById("fa-prod-other");
  if (!otherInput) return;
  if (val === "Others") {
    otherInput.setAttribute("required", "true");
  } else {
    otherInput.removeAttribute("required");
  }
}

function triggerSubmitWarning(formId) {
  const form = document.getElementById(formId);
  if (form.checkValidity()) {
    document.getElementById("confirm-feedback").value = "";
    document.getElementById("confirm-feedback-count").textContent = "0 / 400";
    document.getElementById("confirm-modal-overlay").dataset.target = formId;
    document.getElementById("confirm-modal-overlay").classList.add("active");
  } else {
    form.reportValidity();
  }
}
