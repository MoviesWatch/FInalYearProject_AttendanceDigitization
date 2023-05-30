import { useEffect, useState } from "react";
import { Input } from "../components/Input";
import { Select } from "../components/Select";
import { fetch } from "../helpers/fetch";
import "./../styles/createAcademicSemester.scss";
import "react-alert-confirm/lib/style.css";
import AlertConfirm from "react-alert-confirm";
export const CreateSemester = () => {
  const [semester, setSemester] = useState({
    startYear: new Date().getFullYear(),
    endYear: new Date().getFullYear() + 1,
    semesterType: "",
  });
  const [serverError, setServerError] = useState("");
  const addHandler = async (ev) => {
    ev.preventDefault();
    if (!(semester.startYear && semester.endYear && semester.type != ""))
      return;
    const confirmance = await AlertConfirm("Are you sure?");
    if (!confirmance[0]) return;
    fetch({
      url: "/academicSemester",
      method: "post",
      body: semester,
      setError: setServerError,
      setResult: AlertConfirm.alert,
    });
  };
  return (
    <>
      <form action="" className="create-academic-semester-form">
        <Input
          type="number"
          min="2019"
          max="2100"
          label="Start year"
          layoutType="inline"
          isField={true}
          value={semester.startYear}
          onChange={(ev) =>
            setSemester({ ...semester, startYear: ev.target.value })
          }
        />
        <Input
          type="number"
          min="2019"
          max="2100"
          label="End year"
          layoutType="inline"
          isField={true}
          value={semester.endYear}
          onChange={(ev) =>
            setSemester({ ...semester, endYear: ev.target.value })
          }
        />
        <Select
          label="Even or odd"
          options={[
            ["Odd", "0"],
            ["Even", "1"],
          ]}
          layoutType="inline"
          value={semester.semesterType}
          onChange={(ev) =>
            setSemester({ ...semester, semesterType: ev.target.value })
          }
        />
        <button
          disabled={
            !(
              semester.startYear &&
              semester.endYear &&
              semester.semesterType != ""
            )
          }
          onClick={addHandler}
        >
          Create
        </button>
      </form>
      {serverError && <small className="server-error">{serverError}</small>}
    </>
  );
};
