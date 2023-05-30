import { Input } from "../components/Input";
import { Brand } from "../components/Brand";
import { Password } from "../components/Password";
import "./../styles/login.scss";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { fetch } from "../helpers/fetch";
export const Login = () => {
  const navigate = useNavigate();
  const [credentials, setCredentials] = useState({
    username: "",
    password: "",
  });
  const [serverError, setServerError] = useState("");
  const handleSubmit = async (ev) => {
    ev.preventDefault();
    ev.target.classList.add("active");
    if (!(credentials.username && credentials.password)) return;
    await fetch({
      url: "/admin/login",
      method: "post",
      body: credentials,
      setError: setServerError,
      setResult: (result) => {
        sessionStorage.setItem("admin", JSON.stringify(result));
        navigate("/");
      },
    });
  };
  return (
    <div className="login-container">
      <form action="" onSubmit={handleSubmit}>
        <Brand />
        <Input
          type="text"
          label="username"
          layoutType="block"
          isField={true}
          pattern={/^\w{3,}/}
          onChange={(ev) =>
            setCredentials({ ...credentials, username: ev.target.value })
          }
          value={credentials.username}
        />
        <Password
          layoutType="block"
          isField={true}
          label="Password"
          pattern={/^\w{4,}/}
          onChange={(ev) =>
            setCredentials({
              ...credentials,
              password: ev.target.value,
            })
          }
          value={credentials.password}
        />
        <button>Login</button>
      </form>
      {serverError && <small className="server-error">{serverError}</small>}
    </div>
  );
};
