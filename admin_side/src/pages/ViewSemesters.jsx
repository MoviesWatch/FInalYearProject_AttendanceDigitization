import { useEffect, useState } from "react";
import { fetch } from "../helpers/fetch";
import { CustomTable } from "./../components/CustomTable";
import "react-alert-confirm/lib/style.css";
import AlertConfirm from "react-alert-confirm";
export const ViewSemesters = () => {
  const [semesters, setSemesters] = useState([]);
  const [serverError, setServerError] = useState("");
  const getHandler = () => {
    fetch({
      url: "/academicSemester",
      setError: setServerError,
      setResult: (result) => {
        setSemesters(result);
        if (result.length == 0) {
          setServerError("Nothing to show");
        } else {
          setServerError("");
        }
      },
    });
  };
  useEffect(() => {
    getHandler();
  }, []);
  const updateHandler = (semester, prop) => {
    fetch({
      url: `/academicSemester/${semester.id}`,
      method: "put",
      body: {
        [prop]: semester[prop] == 0 ? "1" : "0",
      },
    });
    getHandler();
  };
  const deleteHandler = async (semester) => {
    const confirmance = await AlertConfirm("Are you sure?");
    if (!confirmance[0]) return;
    fetch({
      url: `/academicSemester/${semester.id}/${semester.databaseName}`,
      method: "delete",
      setError: setServerError,
      setResult: AlertConfirm.alert,
    });
    getHandler();
  };
  return (
    <>
      {semesters.length > 0 && (
        <CustomTable titles={["S.No", "Name", "Actions"]}>
          {semesters.map((semester, index) => (
            <tr>
              <td>{index + 1}</td>
              <td>{semester.name}</td>
              <td>
                <>
                  <button
                    className={
                      "small " + (semester.sActive == 1 ? "" : "inactive")
                    }
                    onClick={() => updateHandler(semester, "sActive")}
                  >
                    Student active
                  </button>
                  <button
                    className={
                      "small " + (semester.fActive == 1 ? "" : "inactive")
                    }
                    onClick={() => updateHandler(semester, "fActive")}
                  >
                    Faculty active
                  </button>
                  <button
                    className="small"
                    onClick={() => deleteHandler(semester)}
                  >
                    Remove
                  </button>
                </>
              </td>
            </tr>
          ))}
        </CustomTable>
      )}
      {serverError && <small className="server-error">{serverError}</small>}
    </>
  );
};
