import * as React from "react";
import { Route, HashRouter as Router, Switch } from "react-router-dom";
import Login from "../pages/Login";
import Signup from "../pages/Signup";

const App: React.FC = () => {
  return (
    <Router>
      <Switch>
        <Route path="/login">
          <Login />
        </Route>
        <Route path="/signup">
          <Signup />
        </Route>
      </Switch>
    </Router>
  );
};

export default App;
