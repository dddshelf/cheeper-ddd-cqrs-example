import * as React from "react";

export interface Context {
  apiDocUri?: string;
}

export const AppContext = React.createContext<Context>({});
