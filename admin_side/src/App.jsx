import "./App.css";
import { Home } from "./pages/Home";
import { Login } from "./pages/Login";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import { ProtectedRoute } from "./helpers/ProtectedRoute";
import axios, { Axios } from "axios";
import { ChangePassword } from "./pages/ChangePassword";
import { ViewSemesters } from "./pages/ViewSemesters";
import { CreateSemester } from "./pages/CreateSemester";

function App() {
  axios.defaults.baseURL = "/academicSemester/api";
  const router = createBrowserRouter(
    [
      {
        path: "/",
        element: <ProtectedRoute route={<Home />} />,
        children: [
          {
            path: "changePassword",
            element: <ChangePassword />,
          },
          {
            path: "viewAcademicSemesters",
            element: <ViewSemesters />,
          },
          {
            path: "createAcademicSemester",
            element: <CreateSemester />,
          },
        ],
      },
      {
        path: "/login",
        element: <ProtectedRoute route={<Login />} login={true} />,
      },
    ],
    {
      basename: "/admin",
    }
  );
  return <RouterProvider router={router} />;
}

export default App;
