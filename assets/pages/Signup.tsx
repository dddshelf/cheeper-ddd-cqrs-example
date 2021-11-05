import React, { useState } from "react";
import * as Api from "../api";
import CheeperLogo from "../components/CheeperLogo";
import * as s from "superstruct";

const Signup: React.FC = () => {
  const [email, setEmail] = useState<string>("");
  const [userName, setUsername] = useState<string>("");
  const [name, setName] = useState<string>("");
  const [location, setLocation] = useState<string>("");
  const [website, setWebsite] = useState<string>("");
  const [password, setPassword] = useState<string>("");
  const [biography, setBiography] = useState<string>("");

  const handleSubmit = async () => {
    const data: Api.SignupData = {
      email,
      userName,
      name,
      location,
      website,
      password,
      biography,
    };

    const signupSchema = s.object({
      email: s.string(),
      userName: s.string(),
      name: s.string(),
      location: s.optional(s.string()),
      website: s.optional(s.string()),
      password: s.string(),
      biography: s.optional(s.string()),
    });

    try {
      s.assert(data, signupSchema);
      await Api.signupUser(data);
    } catch (error) {}
  };

  return (
    <div className="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-4xl w-full space-y-8">
        <div>
          <div className="md:grid md:grid-cols-3 md:gap-6">
            <div className="md:col-span-1">
              <div className="px-4 sm:px-0">
                <CheeperLogo height="28" additionalClasses={["mb-5"]} />
                <h3 className="text-lg font-medium leading-6 text-gray-900">
                  Sign up
                </h3>
                <p className="mt-1 text-sm text-gray-600">
                  Fill the form to create a new account.
                </p>
              </div>
            </div>
            <div className="mt-5 md:mt-0 md:col-span-2">
              <div className="shadow sm:rounded-md sm:overflow-hidden">
                <form onSubmit={handleSubmit}>
                  <div className="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                      <label
                        htmlFor="username"
                        className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                      >
                        Username
                      </label>
                      <div className="mt-1 sm:mt-0 sm:col-span-2">
                        <div className="max-w-lg flex rounded-md shadow-sm">
                          <span className="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                            cheeper.com/
                          </span>
                          <input
                            type="text"
                            name="username"
                            id="username"
                            value={userName}
                            className="flex-1 block w-full focus:ring-cheeper-dark-blue focus:border-cheeper-blue min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300"
                            onChange={(e) => setUsername(e.target.value)}
                          />
                        </div>
                        <span className="text-red-400">Holaaa!!!</span>
                      </div>
                    </div>
                    <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label
                        htmlFor="password"
                        className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                      >
                        Password
                      </label>
                      <div className="mt-1 sm:mt-0 sm:col-span-2">
                        <div className="max-w-lg flex rounded-md shadow-sm">
                          <input
                            type="password"
                            name="password"
                            id="password"
                            value={password}
                            className="block max-w-lg w-full shadow-sm focus:ring-cheeper-blue focus:border-cheeper-dark-blue sm:text-sm border-gray-300 rounded-md"
                            onChange={(e) => setPassword(e.target.value)}
                          />
                        </div>
                      </div>
                    </div>
                    <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label
                        htmlFor="email"
                        className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                      >
                        Email address
                      </label>
                      <div className="mt-1 sm:mt-0 sm:col-span-2">
                        <div className="max-w-lg flex rounded-md shadow-sm">
                          <input
                            id="email"
                            name="email"
                            type="email"
                            className="block max-w-lg w-full shadow-sm focus:ring-cheeper-blue focus:border-cheeper-dark-blue sm:text-sm border-gray-300 rounded-md"
                            onChange={(e) => setEmail(e.target.value)}
                            value={email}
                          />
                        </div>
                      </div>
                    </div>
                    <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label
                        htmlFor="name"
                        className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                      >
                        Full name
                      </label>
                      <div className="mt-1 sm:mt-0 sm:col-span-2">
                        <div className="max-w-lg flex rounded-md shadow-sm">
                          <input
                            type="text"
                            name="name"
                            id="name"
                            value={name}
                            className="block max-w-lg w-full shadow-sm focus:ring-cheeper-blue focus:border-cheeper-dark-blue sm:text-sm border-gray-300 rounded-md"
                            onChange={(e) => setName(e.target.value)}
                          />
                        </div>
                      </div>
                    </div>

                    <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label
                        htmlFor="biography"
                        className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                      >
                        Biography
                      </label>
                      <div className="mt-1 sm:mt-0 sm:col-span-2">
                        <textarea
                          id="biography"
                          name="biography"
                          rows={3}
                          className="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"
                          value={biography}
                          onChange={(e) => setBiography(e.target.value)}
                        />
                        <p className="mt-2 text-sm text-gray-500">
                          Write a few sentences about yourself.
                        </p>
                      </div>
                    </div>
                    <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label
                        htmlFor="location"
                        className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                      >
                        Location
                      </label>
                      <div className="mt-1 sm:mt-0 sm:col-span-2">
                        <div className="max-w-lg flex rounded-md shadow-sm">
                          <input
                            type="text"
                            name="location"
                            id="location"
                            value={location}
                            className="block max-w-lg w-full shadow-sm focus:ring-cheeper-blue focus:border-cheeper-dark-blue sm:text-sm border-gray-300 rounded-md"
                            onChange={(e) => setLocation(e.target.value)}
                          />
                        </div>
                      </div>
                    </div>
                    <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label
                        htmlFor="website"
                        className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                      >
                        Website
                      </label>
                      <div className="mt-1 sm:mt-0 sm:col-span-2">
                        <div className="max-w-lg flex rounded-md shadow-sm">
                          <input
                            type="url"
                            name="website"
                            id="website"
                            value={website}
                            className="block max-w-lg w-full shadow-sm focus:ring-cheeper-blue focus:border-cheeper-dark-blue sm:text-sm border-gray-300 rounded-md"
                            onChange={(e) => setWebsite(e.target.value)}
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button
                      type="submit"
                      className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      Save
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Signup;
