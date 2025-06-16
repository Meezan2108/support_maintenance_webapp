import { usePage } from "@inertiajs/vue3";
import Axios from "axios";

class MilestoneService {

    static list = async (params) => {
        const appBaseUrl = usePage().props.appBaseUrl;
        const response = await Axios.get(`${appBaseUrl}/resources/milestone`, {
            params
        });
        return response;
    };
}

export default MilestoneService;