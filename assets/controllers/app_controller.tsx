import * as React from "react";
import { render } from "react-dom";
import { Controller } from "stimulus";
import App from "../components/App";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  connect() {
    render(
      <React.StrictMode>
        <App />
      </React.StrictMode>,
      this.element
    );
  }
}
