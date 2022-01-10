import * as React from "react";
import { Route, HashRouter as Router, Routes } from "react-router-dom";
import Login from "../pages/Login";
import Signup from "../pages/Signup";

const App: React.FC = () => {
  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/signup" element={<Signup />} />
      </Routes>
    </Router>
  );
};

export default App;
