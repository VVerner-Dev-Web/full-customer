if (localStorage.getItem("full-analytics-session-timeout")) {
  const timeout = parseInt(
    localStorage.getItem("full-analytics-session-timeout")
  );

  const now = new Date();

  if (now.getTime() > timeout && document.referrer === "") {
    localStorage.removeItem("full-analytics-session");
    localStorage.removeItem("full-analytics-session-timeout");
  }
}

if (!localStorage.getItem("full-analytics-session")) {
  let key = "";
  const characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  const charactersLength = characters.length;

  for (i = 0; i < 32; i++) {
    key += characters.charAt(Math.floor(Math.random() * charactersLength));
  }

  const now = new Date();
  const timeout = now.getTime() + parseInt(fullAnalytics.timeoutWindow);

  localStorage.setItem("full-analytics-session-timeout", timeout);
  localStorage.setItem("full-analytics-session", key);
}

jQuery.post(fullAnalytics.endpoint, {
  page: location.pathname,
  session: localStorage.getItem("full-analytics-session"),
  queryString: location.search,
});

const removeLastSlash = (str) => (str.endsWith("/") ? str.slice(0, -1) : str);

const currentLocation = removeLastSlash(
  location.protocol + "//" + location.host + location.pathname
);
const conversions = fullAnalytics.conversions.length
  ? fullAnalytics.conversions
  : [];

const trackConversion = (id) =>
  jQuery.post(fullAnalytics.conversionEndpoint, { id });

conversions
  .filter((c) => c.type === "page:view")
  .forEach((c) => {
    const url = c.element;

    if (removeLastSlash(url) === currentLocation) {
      trackConversion(c.id);
    }
  });

conversions
  .filter((c) => c.type === "element:click")
  .forEach((c) => {
    jQuery(c.element).on("click", () => trackConversion(c.id));
  });

conversions
  .filter((c) => c.type === "element:submit")
  .forEach((c) => {
    jQuery(c.element).on("submit", () => trackConversion(c.id));
  });
