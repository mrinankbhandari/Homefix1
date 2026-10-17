<div class="container" style="padding: 40px 20px;">
    <div class="dashboard-header" style="background:#FEF08A;">
        <div>
            <div class="badge badge-dark" style="margin-bottom:6px;">WDD Technology Requirement 7</div>
            <h2>XML, DTD &amp; XSL <span class="text-gradient">Catalog Engine</span></h2>
            <p>Comprehensive service catalog data represented as XML, validated by Document Type Definition (DTD), and styled via XSLT.</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="public/xml/services.xml" target="_blank" class="btn btn-primary btn-sm">
                Open services.xml ↗
            </a>
            <a href="public/xml/services.dtd" target="_blank" class="btn btn-secondary btn-sm">
                View services.dtd ↗
            </a>
            <a href="public/xml/services.xsl" target="_blank" class="btn btn-secondary btn-sm">
                View services.xsl ↗
            </a>
        </div>
    </div>

    <!-- DTD Validation Status Card -->
    <div style="background:#FFFFFF; border:var(--border-thick); box-shadow:var(--shadow-main); border-radius:var(--radius-lg); padding:24px; margin-bottom:32px;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="font-size:2rem;"><?= $isValid ? '✅' : '⚠️' ?></span>
                <div>
                    <h3 style="font-size:1.3rem; margin:0;">
                        DTD Validation: <?= $isValid ? '<span style="color:#059669;">PASSED &amp; VALID</span>' : '<span style="color:#BE123C;">FAILED</span>' ?>
                    </h3>
                    <p style="font-size:0.88rem; color:#64748B; margin:0;"><?= htmlspecialchars($validationMsg) ?></p>
                </div>
            </div>

            <button class="btn btn-dark btn-sm" onclick="recheckDtdValidation()">
                🔄 Re-run DTD Validation
            </button>
        </div>
    </div>

    <!-- Tabbed Code & Preview Section -->
    <div style="background:#FFFFFF; border:var(--border-thick); box-shadow:var(--shadow-heavy); border-radius:var(--radius-lg); overflow:hidden;">
        <!-- Tab Bar -->
        <div style="background:#0F172A; padding:12px 20px; display:flex; gap:10px; border-bottom:var(--border-thick); flex-wrap:wrap;">
            <button class="xml-tab-btn active" onclick="switchXmlTab('preview', this)">
                🌐 XSLT Transformed Live Preview
            </button>
            <button class="xml-tab-btn" onclick="switchXmlTab('xml', this)">
                📄 services.xml Source
            </button>
            <button class="xml-tab-btn" onclick="switchXmlTab('dtd', this)">
                📐 services.dtd Definition
            </button>
            <button class="xml-tab-btn" onclick="switchXmlTab('xsl', this)">
                🎨 services.xsl Stylesheet
            </button>
        </div>

        <!-- Tab 1: Live XSLT Preview in iframe -->
        <div id="tab-preview" class="xml-tab-content active" style="padding:0;">
            <iframe src="public/xml/services.xml" style="width:100%; height:640px; border:none; display:block;"></iframe>
        </div>

        <!-- Tab 2: XML Code -->
        <div id="tab-xml" class="xml-tab-content" style="display:none; padding:24px; background:#1E293B; color:#F8FAFC; max-height:640px; overflow-y:auto; font-family:monospace; font-size:0.9rem; white-space:pre-wrap;">
<?= htmlspecialchars($xmlContent) ?>
        </div>

        <!-- Tab 3: DTD Code -->
        <div id="tab-dtd" class="xml-tab-content" style="display:none; padding:24px; background:#1E293B; color:#F8FAFC; max-height:640px; overflow-y:auto; font-family:monospace; font-size:0.9rem; white-space:pre-wrap;">
<?= htmlspecialchars($dtdContent) ?>
        </div>

        <!-- Tab 4: XSL Code -->
        <div id="tab-xsl" class="xml-tab-content" style="display:none; padding:24px; background:#1E293B; color:#F8FAFC; max-height:640px; overflow-y:auto; font-family:monospace; font-size:0.9rem; white-space:pre-wrap;">
<?= htmlspecialchars($xslContent) ?>
        </div>
    </div>
</div>

<style>
    .xml-tab-btn {
        background: transparent;
        border: 1.5px solid #334155;
        color: #94A3B8;
        padding: 8px 16px;
        border-radius: 8px;
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .xml-tab-btn:hover {
        color: #FFFFFF;
        border-color: #64748B;
    }
    .xml-tab-btn.active {
        background: #6C5CE7;
        color: #FFFFFF;
        border-color: #6C5CE7;
    }
</style>

<script>
    function switchXmlTab(tabName, btn) {
        document.querySelectorAll('.xml-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.xml-tab-content').forEach(c => c.style.display = 'none');
        const activeTab = document.getElementById('tab-' + tabName);
        if (activeTab) {
            activeTab.style.display = 'block';
        }
    }

    function recheckDtdValidation() {
        fetch('index.php?page=xml-validate')
            .then(res => res.json())
            .then(data => {
                window.HomeFixToast(data.message, data.valid ? 'success' : 'error');
            })
            .catch(err => {
                window.HomeFixToast('Validation check failed.', 'error');
            });
    }
</script>
