import React from "react";

interface Props {
  error: string | null;
}

const FormError: React.FC<Props> = ({ error }) => {
  if (null !== error) {
    return <span className="text-red-400">{error}</span>;
  }

  return null;
};

export default FormError;
