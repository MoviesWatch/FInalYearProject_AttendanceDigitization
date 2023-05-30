import { NavLink, Outlet } from "react-router-dom";
import { Navbar } from "./../components/Navbar";
import "./../styles/home.scss";
import "./../styles/button.scss";
import { session } from "../helpers/getSession";
export const Home = () => {
  return (
    <>
      <Navbar username={session()?.username} />
      <section className="container">
        <ul className="links">
          <li>
            <NavLink to="viewAcademicSemesters">
              View academic semesters
            </NavLink>
          </li>
          <li>
            <NavLink to="createAcademicSemester">
              Create academic semester
            </NavLink>
          </li>
          <li>
            <NavLink to="changePassword">Change password</NavLink>
          </li>
        </ul>
        <Outlet />
      </section>
    </>
  );
};
