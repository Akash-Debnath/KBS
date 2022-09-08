import { API_URL } from "../config/Constants";
import Axios from "axios";

export const ApiCallMaker = async (method, url, data = [], token = "",params="") => {
   
    const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer '+token
    }
    try {
        const response = await Axios({
            method: method,
            url: API_URL+url,
            // baseURL: API_URL,
            data: data,
            headers: headers,
            params: params,
    
        });
        return response;
    } catch (e) {
        console.log("Error:");
        console.log(e);
        return null;
    }
}