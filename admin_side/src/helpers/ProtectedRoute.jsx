import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { session } from "./getSession";

export const ProtectedRoute = ({ route, login }) => {
  const navigate = useNavigate();
  const admin = session().username;
  useEffect(() => {
    if (!admin) navigate("/login", { replace: true });
    if (admin && login) navigate("/", { replace: true });
  }, []);
  return route;
};
