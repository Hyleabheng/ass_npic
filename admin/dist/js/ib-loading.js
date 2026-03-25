// Global loading overlay (auto-injected). Designed to work even if React fails to load.
(function () {
  const MIN_VISIBLE_MS = 800; // Reduced time
  const INITIAL_VISIBLE_MS = 800;
  const OVERLAY_ID = "ibGlobalLoadingOverlay";
  const ROOT_ID = "ibGlobalLoadingRoot";

  let visibleSince = 0;
  let hideTimer = null;
  let isMounted = false;

  function ensureBaseDom() {
    if (document.getElementById(OVERLAY_ID)) return;

    const overlay = document.createElement("div");
    overlay.id = OVERLAY_ID;
    overlay.setAttribute("aria-hidden", "true");
    overlay.style.cssText = [
      "position:fixed",
      "inset:0",
      "display:none",
      "align-items:center",
      "justify-content:center",
      "background:rgba(15,23,42,.55)",
      "backdrop-filter:blur(6px)",
      "-webkit-backdrop-filter:blur(6px)",
      "z-index:99999",
      "padding:24px"
    ].join(";");

    const root = document.createElement("div");
    root.id = ROOT_ID;
    root.style.cssText = "width:auto;min-width:140px;max-width:360px;";

    overlay.appendChild(root);
    document.body.appendChild(overlay);
  }

  function mountIfPossible() {
    if (isMounted) return;
    const rootEl = document.getElementById(ROOT_ID);
    if (!rootEl) return;

    // Fallback UI (no React)
    rootEl.innerHTML = [
      '<div style="background:#ffffff;border-radius:12px;padding:24px 32px;box-shadow:0 8px 30px rgba(0,0,0,.2);display:flex;align-items:center;justify-content:center;gap:12px;flex-direction:column;text-align:center;box-sizing:border-box;">',
      '  <div style="font-size:14px;color:#555;font-weight:500;letter-spacing:0.2px">Loading...</div>',
      '  <div style="width:36px;height:36px;border-radius:50%;border:4px solid transparent;border-top-color:#1a73e8;border-bottom-color:#1a73e8;animation:ibSpin 0.65s linear infinite"></div>',
      "</div>"
    ].join("");

    // React render (optional)
    try {
      if (!window.React || !window.ReactDOM || !window.ReactDOM.createRoot) {
        isMounted = true;
        return;
      }
      const e = window.React.createElement;
      const Card = function Card(props) {
        return e(
          "div",
          {
            style: {
              background: "#ffffff",
              padding: "24px 32px",
              boxShadow: "0 8px 30px rgba(0,0,0,.2)",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              gap: 12,
              flexDirection: "column",
              textAlign: "center",
              boxSizing: "border-box"
            }
          },
          props.children
        );
      };
      const Spinner = function Spinner() {
        return e("div", {
          style: {
            width: 36,
            height: 36,
            borderRadius: "50%",
            border: "4px solid transparent",
            borderTopColor: "#1a73e8",
            borderBottomColor: "#1a73e8",
            animation: "ibSpin 0.65s linear infinite"
          }
        });
      };
      const Text = function Text() {
        return e(
          "div",
          { style: { fontSize: 14, color: "#555", fontWeight: 500, letterSpacing: 0.2 } },
          "Loading..."
        );
      };
      const App = function App() {
        return e(Card, null, e(Text, null), e(Spinner, null));
      };

      window.ReactDOM.createRoot(rootEl).render(e(App));
      isMounted = true;
    } catch (_) {
      isMounted = true;
    }
  }

  function setVisible(nextVisible) {
    const overlay = document.getElementById(OVERLAY_ID);
    if (!overlay) return;
    overlay.style.display = nextVisible ? "flex" : "none";
    overlay.setAttribute("aria-hidden", nextVisible ? "false" : "true");
  }

  function showLoading() {
    ensureBaseDom();
    mountIfPossible();
    if (hideTimer) {
      clearTimeout(hideTimer);
      hideTimer = null;
    }
    if (!visibleSince) visibleSince = Date.now();
    setVisible(true);
  }

  function hideLoading() {
    const elapsed = visibleSince ? Date.now() - visibleSince : 0;
    const remaining = Math.max(0, MIN_VISIBLE_MS - elapsed);
    if (hideTimer) clearTimeout(hideTimer);
    hideTimer = setTimeout(function () {
      visibleSince = 0;
      setVisible(false);
    }, remaining);
  }

  // Expose API
  window.iBLoading = {
    show: showLoading,
    hide: hideLoading
  };

  // Inject keyframes and global overrides once
  (function ensureKeyframes() {
    if (document.getElementById("ibLoadingKeyframes")) return;
    const style = document.createElement("style");
    style.id = "ibLoadingKeyframes";
    style.textContent = `
      @keyframes ibSpin { to { transform: rotate(360deg) } }
      .dataTables_processing, .jsgrid-load-panel, .overlay-wrapper .overlay { display: none !important; }
    `;
    document.head.appendChild(style);
  })();

  // Auto show on initial load (brief)
  function onReady(fn) {
    if (document.readyState === "complete" || document.readyState === "interactive") {
      fn();
      return;
    }
    document.addEventListener("DOMContentLoaded", fn, { once: true });
  }

  onReady(function () {
    showLoading();
    setTimeout(hideLoading, INITIAL_VISIBLE_MS);

    // Show on all form submits
    document.addEventListener(
      "submit",
      function (e) {
        showLoading();
        setTimeout(function() {
          if (!e.defaultPrevented) hideLoading();
        }, MIN_VISIBLE_MS + 2000);
      },
      true
    );

    // Global jQuery AJAX bindings for background forms & tables
    if (window.jQuery) {
      window.jQuery(document).ajaxStart(function () { showLoading(); });
      window.jQuery(document).ajaxStop(function () { hideLoading(); });
    }

    // Show on normal link clicks (navigation)
    document.addEventListener(
      "click",
      function (e) {
        const a = e.target && e.target.closest ? e.target.closest("a") : null;
        if (!a) return;
        if (a.hasAttribute("download")) return;
        const href = a.getAttribute("href") || "";
        if (!href || href.startsWith("#") || href.startsWith("javascript:")) return;
        const target = (a.getAttribute("target") || "").toLowerCase();
        if (target === "_blank") return;
        showLoading();
      },
      true
    );
  });
})();

