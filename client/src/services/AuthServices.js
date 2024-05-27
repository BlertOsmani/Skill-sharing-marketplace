import axios from "axios";

const API_URL = "http://127.0.0.1:8000/api";

export default {
  getProfile() {
    return axios.get(`${API_URL}/user`, {
      headers: {
        Authorization: `bearer ${localStorage.getItem("authToken")}`,
      },
    });
  },
};
