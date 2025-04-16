<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
$SAP_Mingle_Update = new SAP_Mingle_Update();
$license_data = $SAP_Mingle_Update->get_license_data();
if (!$sap_common->sap_is_license_activated()) {
    $redirection_url = '/mingle-update/';
    header('Location: ' . SAP_SITE_URL . $redirection_url);
    die();
}

include 'header.php';

include 'sidebar.php';


// Get user's active networks
$networks = $this->networkTypes();

?>

<style>
    .import_excel_user_and_bot {
        .box {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 1000px;
            width: 100%;
            text-align: center;
            margin: 40px auto;
            transition: all 0.3s ease;
        }

        h2 {
            color: #2563eb;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .drop-zone {
            border: 2px dashed #60a5fa;
            border-radius: 16px;
            padding: 50px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            background: #f8fafc;
            color: #2563eb;
            font-size: 16px;
            position: relative;
        }

        .drop-zone.dragover {
            background-color: #e0f2fe;
        }

        .drop-zone input {
            display: none;
        }

        .file-name {
            margin-top: 1rem;
            font-weight: 500;
            color: #0f172a;
            font-size: 14px;
        }

        button {
            margin-top: 2rem;
            background: #3b82f6;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #2563eb;
        }

        .fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .SelectContiner{
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .form-group {
            margin: 1.5rem;
            width: 100%;
        }

        .selectLabel {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .select {
            width: 100%;
            max-width: 250px;
            padding: 0.5rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: white;
        }
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
			<span class="d-flex flex-wrap align-items-center">
				                             <span class="margin-r-5"><i class="fa fa-user-secret"></i></span>

			                <?php eLang('bots_title'); ?>
			</span>
        </h1>
    </section>
    <!-- Main content -->
    <div class="content">
        <!-- Info boxes -->
        <?php echo $this->flash->renderFlash() ?>
        <div class="import_excel_user_and_bot">
            <div class="box fadeIn">
                <h2>Upload Excel File</h2>
                <form class="import-bots-form" name="import_bots" id="import_bots" method="POST"
                      enctype="multipart/form-data" action="<?php echo SAP_SITE_URL . '/bots/import/excel/user_and_bot/'; ?>">
                    <div class="drop-zone" id="drop-zone">
                        <span>📂 Drag & Drop your Excel file here<br>or click to choose</span>
                        <input type="file" name="file" id="file" accept=".xls,.xlsx">
                        <div class="file-name" id="file-name"></div>
                    </div>
                    <div class="SelectContiner">
                        <div class="form-group">
                            <label for="user-select" class="selectLabel">User</label>
                            <select id="user-select" name="user_id" class="select">
                                <option value="">-- Choose a User --</option>
                                <?php foreach ($this->getAllUsers() as $user) { ?>
                                    <option value="<?php echo $user->id ?>"  <?php echo $user->id == ($_SESSION['form_data']['user_id'] ?? '') ? 'selected' : '' ?> ><?php echo $user->email ?></option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="bot-select" class="selectLabel">Bot</label>
                            <select id="bot-select" name="bot_id" class="select" disabled>
                                <option value="">-- Select a User First --</option>
                            </select>
                        </div>
                    </div>


                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>
    <?php
    unset($_SESSION['sap_active_tab']);
    include 'footer.php';
    ?>

<script>
    const dropZone = document.getElementById("drop-zone");
    const fileInput = document.getElementById("file");
    const fileNameDisplay = document.getElementById("file-name");

    dropZone.addEventListener("click", () => fileInput.click());

    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("dragover");
    });

    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("dragover");
        const files = e.dataTransfer.files;
        if (files.length && /\.(xls|xlsx)$/i.test(files[0].name)) {
            fileInput.files = files;
            fileNameDisplay.textContent = `✅ ${files[0].name}`;
        } else {
            fileNameDisplay.textContent = "❌ Please drop a valid Excel file (.xls or .xlsx)";
        }
    });

    fileInput.addEventListener("change", () => {
        if (fileInput.files.length) {
            fileNameDisplay.textContent = `✅ ${fileInput.files[0].name}`;
        }
    });
</script>


<script>
    const userSelect = document.getElementById("user-select");
    const botSelect = document.getElementById("bot-select");


    userSelect.addEventListener("change", () => {
        const selectedUser = userSelect.value;
        botSelect.innerHTML = "";

        if(! selectedUser) {
            $(botSelect).empty();
            $(botSelect).attr('disabled',true);
            return;
        }
        $.ajax({
            url: "<?php echo $router->generate('bots') ?>",
            method: "GET",
            data: { userId: selectedUser },
            success: function (res) {
                $(botSelect).empty();
                $(botSelect).attr('disabled',false);

                $.each(res.data.bots, function (index, bot) {
                    $(botSelect).append(
                        $("<option></option>").val(bot.id).text(bot.type + ' ' + bot.target)
                    );
                });


            },
            error: function (xhr, status, error) {
                console.error("خطا در دریافت بات‌ها:", error);
            }
        });

    });

    <?php if(isset($_SESSION['form_data']['user_id']) && $_SESSION['form_data']['user_id'] ) { ?>
        userSelect.dispatchEvent(new Event('change'));
        <?php unset($_SESSION['form_data']) ?>
    <?php } ?>
</script>
