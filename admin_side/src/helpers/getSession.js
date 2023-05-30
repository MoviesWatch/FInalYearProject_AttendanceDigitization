export const session = () => JSON.parse(sessionStorage.getItem("admin")) || {};
