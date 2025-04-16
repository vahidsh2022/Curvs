<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

global $sap_common;

$quickPosts = $this->getQuickPosts(true);

?>

<link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/calender.css' ?>">


<div class="content-wrapper">
    <div class="max-w-[1400px] mx-auto my-6 border rounded-md font-sans">
        <div class="w-full flex justify-between border px-8 py-4 mb-4 border-t-0 border-l-0 border-r-0 items-center">
            <h2 class="text-blue-500 font-bold text-2xl">
                <span class="margin-r-5"><i class="fa fa-calendar"></i></span>
                <?php echo $sap_common->lang('calender_title') ?></h2>
            <button class="bg-blue-500 p-2 text-center text-white rounded">
                <span>Add Event</span>
                <span>+</span>
            </button>
        </div>

        <div class="w-full">
            <div class="flex flex-wrap w-full justify-between items-center px-4 py-2">
                <div class="flex items-center w-full sm:w-auto mb-2 sm:mb-0">
                    <button class="bg-[#f9f9f9] text-blue-400 hover:text-blue-600 transition-all px-2 py-1 text-2xl mx-1 rounded my-2 text-center">
                        &lt;
                    </button>
                    <button class="bg-[#f9f9f9] text-blue-400 hover:text-blue-600 transition-all px-2 py-1 text-2xl mx-1 rounded my-2 text-center">
                        &gt;
                    </button>
                    <span class="bg-[#f9f9f9] rounded text-gray-500 px-4 py-2 text-center">Today</span>
                </div>

                <h3 class="text-blue-500 font-bold text-lg w-full sm:w-auto text-center sm:text-left mb-2 sm:mb-0">March
                    2025</h3>

                <div class="flex items-center bg-[#f9f9f9] px-4 py-2 rounded text-blue-500 font-black w-full sm:w-auto justify-center sm:justify-end">
                    <button class="mx-2 px-2 bg-blue-500 rounded text-white w-1/3 sm:w-auto">Month</button>
                    <button class="mx-2 px-2 w-1/3 sm:w-auto">Week</button>
                    <button class="mx-2 px-2 w-1/3 sm:w-auto">Day</button>
                </div>
            </div>


            <div class="my-2 px-4 py-2">
                <table class="w-full my-4 border-collapse border">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="rounded px-6 py-4 text-blue-500 font-bold text-lg border text-center">Sun</th>
                        <th class="rounded px-6 py-4 text-blue-500 font-bold text-lg border text-center">Mon</th>
                        <th class="rounded px-6 py-4 text-blue-500 font-bold text-lg border text-center">Tue</th>
                        <th class="rounded px-6 py-4 text-blue-500 font-bold text-lg border text-center">Wed</th>
                        <th class="rounded px-6 py-4 text-blue-500 font-bold text-lg border text-center">Thu</th>
                        <th class="rounded px-6 py-4 text-blue-500 font-bold text-lg border text-center">Fri</th>
                        <th class="rounded px-6 py-4 text-blue-500 font-bold text-lg border text-center">Sat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white hover:bg-gray-100 transition">
                        <td class="relative px-6 py-12 border align-top">
                            <span class="absolute top-2 left-2 text-xs font-bold text-gray-600">1</span>
                            <div class="flex flex-col mt-6 space-y-1">
                                <?php
                                foreach (array_values(array_filter($quickPosts, fn($item) => $item->day_name === 'Sunday')) as $quickPost) {
                                    include(SAP_APP_PATH . 'view/Calender/partials/badge_of_calender_item.php');
                                }
                                $quickPost = null;
                                ?>
                            </div>
                        </td>
                        <td class="relative px-6 py-12 border align-top">
                            <span class="absolute top-2 left-2 text-xs font-bold text-gray-600">2</span>
                            <div class="flex flex-col mt-6 space-y-1">
                                <?php
                                foreach (array_values(array_filter($quickPosts, fn($item) => $item->day_name === 'Monday')) as $quickPost) {
                                    include(SAP_APP_PATH . 'view/Calender/partials/badge_of_calender_item.php');
                                }
                                $quickPost = null;
                                ?>
                            </div>
                        </td>
                        <td class="relative px-6 py-12 border align-top">
                            <span class="absolute top-2 left-2 text-xs font-bold text-gray-600">3</span>
                            <div class="flex flex-col mt-6 space-y-1">
                                <?php
                                foreach (array_filter($quickPosts, fn($item) => $item->day_name === 'Tuesday') as $quickPost) {
                                    include(SAP_APP_PATH . 'view/Calender/partials/badge_of_calender_item.php');
                                }
                                $quickPost = null;
                                ?>
                            </div>
                        </td>
                        <td class="relative px-6 py-12 border align-top">
                            <span class="absolute top-2 left-2 text-xs font-bold text-gray-600">4</span>
                            <div class="flex flex-col mt-6 space-y-1">
                                <?php
                                foreach (array_values(array_filter($quickPosts, fn($item) => $item->day_name === 'Wednesday')) as $quickPost) {
                                    include(SAP_APP_PATH . 'view/Calender/partials/badge_of_calender_item.php');
                                }
                                $quickPost = null;
                                ?>
                            </div>
                        </td>
                        <td class="relative px-6 py-12 border align-top">
                            <span class="absolute top-2 left-2 text-xs font-bold text-gray-600">5</span>
                            <div class="flex flex-col mt-6 space-y-1">
                                <?php
                                foreach (array_filter($quickPosts, fn($item) => $item->day_name === 'Thursday') as $quickPost) {
                                    include(SAP_APP_PATH . 'view/Calender/partials/badge_of_calender_item.php');
                                }
                                $quickPost = null;
                                ?>
                            </div>
                        </td>
                        <td class="relative px-6 py-12 border align-top">
                            <span class="absolute top-2 left-2 text-xs font-bold text-gray-600">6</span>
                            <div class="flex flex-col mt-6 space-y-1">

                                <?php
                                foreach (array_filter($quickPosts, fn($item) => $item->day_name === 'Friday') as $quickPost) {
                                    include(SAP_APP_PATH . 'view/Calender/partials/badge_of_calender_item.php');
                                }
                                $quickPost = null;
                                ?>
                            </div>

                        </td>
                        <td class="relative px-6 py-12 border align-top">
                            <span class="absolute top-2 left-2 text-xs font-bold text-gray-600">7</span>
                            <div class="flex flex-col mt-6 space-y-1">
                                <?php
                                foreach (array_filter($quickPosts, fn($item) => $item->day_name === 'Saturday') as $quickPost) {
                                    include(SAP_APP_PATH . 'view/Calender/partials/badge_of_calender_item.php');
                                }
                                $quickPost = null;
                                ?>
                            </div>
                        </td>
                    </tr>
                    <!-- Repeat for other rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
