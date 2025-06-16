import { usePage } from "@inertiajs/vue3";
import Axios from "axios";

class ExtensionMilestoneService
{
    static show = async (params) => {
        const appBaseUrl = usePage().props.appBaseUrl;
        const response = await Axios.get(
            `${appBaseUrl}/resources/extension-milestone/show`,
            { params }
        );
        return response;
    };
}

export default ExtensionMilestoneService;