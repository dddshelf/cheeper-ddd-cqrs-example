import fetch from "unfetch";

const ApiRoutes: { [key: string]: string } = {
  token: "/api/login/check",
  refreshToken: "/api/token/refresh",
  postUser: "/api/users",
};

type RouteName = "token" | "refreshToken" | "postUser";

export interface ApiError {
  detail: string;
  title: string;
  type: string;
  violations?: Array<{ propertyPath: string; message: string; code: string }>;
}

const apiUri = (routeName: RouteName): string => {
  if (!ApiRoutes.hasOwnProperty(routeName)) {
    throw new Error(`No API route "${routeName}" exists.`);
  }

  return ApiRoutes[routeName];
};

const get = async <T>(uri: string): Promise<T> => (await fetch(uri)).json();

/** @throws ApiError */
const post = async <T>(uri: string, data?: any): Promise<T> => {
  const response = await fetch(uri, {
    method: "POST",
    body: JSON.stringify(data),
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
  });

  if (response.ok) {
    return await response.json();
  }

  throw await response.json();
};

export interface SignupData {
  email: string;
  userName: string;
  name: string;
  location: string;
  website: string;
  password: string;
  biography: string;
}

interface SignupResponse {}

export const signupUser = async (data: SignupData): Promise<SignupResponse> =>
  await post<SignupResponse>(apiUri("postUser"), data);
