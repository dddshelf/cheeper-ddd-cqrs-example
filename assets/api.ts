import axios, { AxiosResponse } from "axios";

const ApiRoutes: { [key: string]: string } = {
  token: "/api/login/check",
  refreshToken: "/api/token/refresh",
  postUser: "/api/users",
};

type RouteName = "token" | "refreshToken" | "postUser";

const apiUri = (routeName: RouteName): string => {
  if (!ApiRoutes.hasOwnProperty(routeName)) {
    throw new Error(`No API route "${routeName}" exists.`);
  }

  return ApiRoutes[routeName];
};

const get = async <T>(uri: string): Promise<AxiosResponse<T>> => axios.get(uri);

const post = async <T>(
  uri: string,
  data?: FormData
): Promise<AxiosResponse<T>> => axios.post(uri, data);

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

export const signupUser = async (data: SignupData): Promise<SignupResponse> => {
  const d = new FormData();
  Object.entries(data).forEach(([value, key]) => d.append(key, value));
  return post<SignupResponse>(apiUri("postUser"), d);
};
