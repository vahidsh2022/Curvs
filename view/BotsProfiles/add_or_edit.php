<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

$profiles = $this->getBotsProfiles();
$firstProfile = $profiles[0] ?? null;
$meta = $firstProfile ? json_decode($firstProfile->meta,true) : null;

$bot = $this->getBot();

?>
    <div class="content-wrapper">
        <section class="content-header content-header-quick-post d-flex justify-content-between">
            <h1>
                <span class="margin-r-5"><i class="fa fa-user-secret"></i></span>

                Account
            </h1>
        </section>

        <section class="content sap-quick-post">

            <?php echo $this->flash->renderFlash(); ?>
            <div class="row">
                <!-- Avatar Section -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-center">
                    <img src="<?php echo $firstProfile && $firstProfile->image ? SAP_IMG_URL . $firstProfile->image : SAP_SITE_URL . '/assets/images/avatar.jpg'; ?>" alt="User Avatar" class="img-fluid rounded-circle img-thumbnail" id="bots_profiles_images_trigger">
                </div>

                <!-- Form Section -->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <h2>User Profile Settings</h2>
                    <form id="identityForm" method="POST" enctype="multipart/form-data" action="<?php echo SAP_SITE_URL . '/bots_profiles/store_or_update/' . $this->getBot()->id . '/'; ?>">
                        <input type="file" name="image" id="bots_profiles_image" style="visibility: hidden">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#Identity" aria-controls="Identity" role="tab" data-toggle="tab" aria-selected="true">Identity</a>
                            </li>
                            <li role="presentation">
                                <a href="#CorePersonalityTraits" aria-controls="CorePersonalityTraits" role="tab" data-toggle="tab">Core Personality Traits</a>
                            </li>
                            <li role="presentation">
                                <a href="#CommunicationStyle" aria-controls="CommunicationStyle" role="tab" data-toggle="tab">Communication Style</a>
                            </li>
                            <li role="presentation">
                                <a href="#KnowledgeAndExpertise" aria-controls="KnowledgeAndExpertise" role="tab" data-toggle="tab">Knowledge and Expertise</a>
                            </li>
                            <li role="presentation">
                                <a href="#BehavioralTendencies" aria-controls="BehavioralTendencies" role="tab" data-toggle="tab">Behavioral Tendencies</a>
                            </li>
                            <li role="presentation">
                                <a href="#SocialAccounts" aria-controls="SocialAccounts" role="tab" data-toggle="tab">Social Accounts</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Identity Tab -->
                            <div role="tabpanel" class="tab-pane active" id="Identity" aria-labelledby="Identity-tab">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Identity</strong></div>
                                    <div class="panel-body" id="identityFields">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $firstProfile ? $firstProfile->name : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <?php foreach ($this->genders() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($firstProfile ? $firstProfile->gender : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ageRange">Age</label>
                                            <div class="range-container" id="ageRangeContainer">
                                                <div class="range-point" id="agePoint" data-value="<?php echo $firstProfile ? $firstProfile->age : '0'; ?>" data-input-name="age"></div>
                                            </div>
                                            <div class="range-label" id="ageLabel"><?php echo $firstProfile ? $firstProfile->age : '0'; ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" id="country" name="country" value="<?php echo $firstProfile ? $firstProfile->country : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $firstProfile ? $firstProfile->city : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Core Personality Traits Tab -->
                            <div role="tabpanel" class="tab-pane" id="CorePersonalityTraits" aria-labelledby="CorePersonalityTraits-tab">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Core Personality Traits</strong></div>
                                    <div class="panel-body">
                                        <!-- Openness -->
                                        <div class="form-group">
                                            <label for="opennessRange">Openness to Experience</label>
                                            <div class="range-container" id="opennessRangeContainer">
                                                <div class="range-point" id="opennessPoint" data-input-name="meta[openness]" data-value="<?php echo $meta ? ($meta['openness'] ?? '0') : '0'; ?>"></div>
                                            </div>
                                            <div class="range-label" id="opennessLabel"><?php echo $meta ? ($meta['openness'] ?? '0') : '0'; ?></div>
                                        </div>
                                        <!-- Conscientiousness -->
                                        <div class="form-group">
                                            <label for="conscientiousness">Conscientiousness</label>
                                            <div class="range-container" id="conscientiousnessRangeContainer">
                                                <div class="range-point" id="conscientiousnessPoint" data-input-name="meta[conscientiousness]" data-value="<?php echo $meta ? ($meta['conscientiousness'] ?? '0') : '0'; ?>"></div>
                                            </div>
                                            <div class="range-label" id="conscientiousnessLabel"><?php echo $meta ? ($meta['conscientiousness'] ?? '0') : '0'; ?></div>
                                        </div>
                                        <!-- Extraversion -->
                                        <div class="form-group">
                                            <label for="extraversion">Extraversion</label>
                                            <div class="range-container" id="extraversionRangeContainer">
                                                <div class="range-point" id="extraversionPoint" data-input-name="meta[extraversion]" data-value="<?php echo $meta ? ($meta['extraversion'] ?? '0') : '0'; ?>"></div>
                                            </div>
                                            <div class="range-label" id="extraversionLabel"><?php echo $meta ? ($meta['extraversion'] ?? '0') : '0'; ?></div>
                                        </div>
                                        <!-- Agreeableness -->
                                        <div class="form-group">
                                            <label for="agreeableness">Agreeableness</label>
                                            <div class="range-container" id="agreeablenessRangeContainer">
                                                <div class="range-point" id="agreeablenessPoint" data-input-name="meta[agreeableness]" data-value="<?php echo $meta ? ($meta['agreeableness'] ?? '0') : '0'; ?>"></div>
                                            </div>
                                            <div class="range-label" id="agreeablenessLabel"><?php echo $meta ? ($meta['agreeableness'] ?? '0') : '0'; ?></div>
                                        </div>
                                        <!-- Neuroticism -->
                                        <div class="form-group">
                                            <label for="neuroticism">Neuroticism</label>
                                            <div class="range-container" id="neuroticismRangeContainer">
                                                <div class="range-point" id="neuroticismPoint" data-input-name="meta[neuroticism]" data-value="<?php echo $meta ? ($meta['neuroticism'] ?? '0') : '0'; ?>"></div>
                                            </div>
                                            <div class="range-label" id="neuroticismLabel"><?php echo $meta ? ($meta['neuroticism'] ?? '0') : '0'; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Communication Style Tab -->
                            <div role="tabpanel" class="tab-pane" id="CommunicationStyle" aria-labelledby="CommunicationStyle-tab">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Communication Style</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="formality">Formality</label>
                                            <select class="form-control" id="formality" name="meta[formality]">
                                                <?php foreach ($this->formalities() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['formality'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tone">Tone</label>
                                            <select class="form-control" id="tone" name="meta[tone]">
                                                <?php foreach ($this->tones() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['tone'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="verbosity">Verbosity</label>
                                            <select class="form-control" id="verbosity" name="meta[verbosity]">
                                                <?php foreach ($this->verbosities() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['verbosity'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="vocabulary">Vocabulary</label>
                                            <select class="form-control" id="vocabulary" name="meta[vocabulary]">
                                                <?php foreach ($this->vocabularies() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['vocabulary'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pacing">Pacing</label>
                                            <select class="form-control" id="pacing" name="meta[pacing]">
                                                <?php foreach ($this->pacings() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['pacing'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="useOfEmojis">Use of Emojis</label>
                                            <select class="form-control" id="useOfEmojis" name="meta[useOfEmojis]">
                                                <?php foreach ($this->useOfEmojis() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['useOfEmojis'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Knowledge and Expertise Tab -->
                            <div role="tabpanel" class="tab-pane" id="KnowledgeAndExpertise" aria-labelledby="KnowledgeAndExpertise-tab">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Knowledge and Expertise</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="domainExpertise">Domain Expertise</label>
                                            <input type="text" class="form-control" id="domainExpertise" name="meta[domainExpertise]" value="<?php echo $meta ? ($meta['domainExpertise'] ?? '') : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="knowledgeDepth">Knowledge Depth</label>
                                            <select class="form-control" id="knowledgeDepth" name="meta[knowledgeDepth]">
                                                <?php foreach ($this->knowledgeDepths() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['knowledgeDepth'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="informationSource">Information Source Preference</label>
                                            <select class="form-control" id="informationSource" name="meta[informationSource]">
                                                <?php foreach ($this->informationSources() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['informationSource'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Behavioral Tendencies Tab -->
                            <div role="tabpanel" class="tab-pane" id="BehavioralTendencies" aria-labelledby="BehavioralTendencies-tab">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Behavioral Tendencies</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="proactiveness">Proactiveness</label>
                                            <select class="form-control" id="proactiveness" name="meta[proactiveness]">
                                                <?php foreach ($this->proactivenesses() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['proactiveness'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="helpfulness">Helpfulness</label>
                                            <select class="form-control" id="helpfulness" name="meta[helpfulness]">
                                                <?php foreach ($this->helpfulnesses() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['helpfulness'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="humor">Humor</label>
                                            <div class="range-container" id="humorRangeContainer">
                                                <div class="range-point" id="humorPoint" data-input-name="meta[humor]" data-value="<?php echo $meta ? ($meta['humor'] ?? '0') : '0'; ?>"></div>
                                            </div>
                                            <div class="range-label" id="humorLabel"><?php echo $meta ? ($meta['humor'] ?? '0') : '0'; ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="patience">Patience</label>
                                            <div class="range-container" id="patienceRangeContainer">
                                                <div class="range-point" id="patiencePoint" data-input-name="meta[patience]" data-value="<?php echo $meta ? ($meta['patience'] ?? '0') : '0'; ?>"></div>
                                            </div>
                                            <div class="range-label" id="patienceLabel"><?php echo $meta ? ($meta['patience'] ?? '0') : '0'; ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="adaptability">Adaptability</label>
                                            <select class="form-control" id="adaptability" name="meta[adaptability]">
                                                <?php foreach ($this->adaptabilities() as $value => $label) { ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == ($meta ? ($meta['adaptability'] ?? '') : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="SocialAccounts">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Social Accounts</strong></div>
                                    <div class="panel-body" id="socialAccountsContainer">
                                        <?php foreach ($profiles as $profile) {
                                            include $sap_common->get_template_path('BotsProfiles' . DS . 'partials/credentials.php');
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <button type="button" id="addFieldButton"  class="btn btn-primary">Add Social Network</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        const networkTypesObject = JSON.parse('<?php echo json_encode($this->networkTypes()) ?>')
        const __networkTypes = [];
        for(const key in networkTypesObject) {
            __networkTypes.push({
                label: networkTypesObject[key],
                value: key,
            })
        }
    </script>
<?php include SAP_APP_PATH . 'footer.php'; ?>