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
  logout() {
    try {
      const response = axios.post(
        `${API_URL}/auth/user/logout`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('authToken')}`,
          },
        }
      );
      localStorage.removeItem('authToken');
      return response.data;
    } catch (error) {
      throw new Error('Failed to log out, please try again');
    }
  },
};
