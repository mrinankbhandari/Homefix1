<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" indent="yes"/>

    <xsl:template match="/services">
        <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <title>HomeFix — XML &amp; XSL Services Catalog</title>
            <link rel="preconnect" href="https://fonts.googleapis.com"/>
            <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
            <style>
                :root {
                    --bg: #F8FAFC;
                    --dark: #0F172A;
                    --violet: #6C5CE7;
                    --amber: #F59E0B;
                    --emerald: #10B981;
                    --coral: #FF7675;
                    --surface: #FFFFFF;
                    --border: 2.5px solid #0F172A;
                    --shadow: 5px 5px 0px #0F172A;
                }
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body {
                    background: var(--bg);
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    color: var(--dark);
                    padding: 40px 20px;
                }
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                }
                .header {
                    background: #FEF08A;
                    border: var(--border);
                    box-shadow: var(--shadow);
                    padding: 32px;
                    border-radius: 16px;
                    margin-bottom: 32px;
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    align-items: center;
                    gap: 20px;
                }
                .header h1 {
                    font-family: 'Outfit', sans-serif;
                    font-size: 2.2rem;
                    font-weight: 900;
                    letter-spacing: -0.5px;
                }
                .badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: var(--dark);
                    color: #fff;
                    padding: 6px 14px;
                    border-radius: 999px;
                    font-size: 0.85rem;
                    font-weight: 700;
                }
                .back-nav {
                    display: inline-block;
                    margin-bottom: 24px;
                    font-weight: 700;
                    color: var(--dark);
                    text-decoration: none;
                    background: #fff;
                    border: var(--border);
                    padding: 8px 16px;
                    border-radius: 10px;
                    box-shadow: 3px 3px 0 #0F172A;
                }
                .cat-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                    gap: 16px;
                    margin-bottom: 36px;
                }
                .cat-card {
                    background: #fff;
                    border: var(--border);
                    box-shadow: 4px 4px 0 #0F172A;
                    border-radius: 12px;
                    padding: 16px;
                    text-align: center;
                }
                .cat-card .icon {
                    font-size: 2rem;
                    margin-bottom: 8px;
                }
                .cat-card h3 {
                    font-family: 'Outfit', sans-serif;
                    font-size: 1.1rem;
                    font-weight: 800;
                }
                .pro-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
                    gap: 24px;
                }
                .pro-card {
                    background: #fff;
                    border: var(--border);
                    box-shadow: var(--shadow);
                    border-radius: 16px;
                    padding: 24px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }
                .pro-head {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 12px;
                }
                .pro-name {
                    font-family: 'Outfit', sans-serif;
                    font-size: 1.4rem;
                    font-weight: 800;
                }
                .tag {
                    display: inline-block;
                    background: #E0E7FF;
                    color: #4338CA;
                    padding: 4px 10px;
                    border-radius: 8px;
                    font-weight: 700;
                    font-size: 0.8rem;
                    border: 1.5px solid #0F172A;
                }
                .meta {
                    display: flex;
                    gap: 14px;
                    font-size: 0.9rem;
                    font-weight: 600;
                    margin-bottom: 14px;
                    color: #475569;
                }
                .star { color: #F59E0B; }
                .bio {
                    font-size: 0.95rem;
                    color: #334155;
                    line-height: 1.5;
                    margin-bottom: 18px;
                }
                .offerings-box {
                    background: #F1F5F9;
                    border: 2px dashed #CBD5E1;
                    border-radius: 12px;
                    padding: 14px;
                    margin-bottom: 20px;
                }
                .offerings-box h4 {
                    font-size: 0.85rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 10px;
                    color: #64748B;
                }
                .service-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 6px 0;
                    border-bottom: 1px solid #E2E8F0;
                    font-size: 0.9rem;
                }
                .service-row:last-child { border-bottom: none; }
                .service-price {
                    font-weight: 800;
                    color: #059669;
                }
                .book-btn {
                    display: block;
                    width: 100%;
                    text-align: center;
                    background: #6C5CE7;
                    color: #fff;
                    font-family: 'Outfit', sans-serif;
                    font-size: 1rem;
                    font-weight: 800;
                    text-decoration: none;
                    padding: 12px;
                    border-radius: 10px;
                    border: var(--border);
                    box-shadow: 3px 3px 0 #0F172A;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <a class="back-nav" href="../../index.php">← Back to HomeFix Marketplace</a>

                <div class="header">
                    <div>
                        <div class="badge">XML + XSLT + DTD Catalog Engine</div>
                        <h1 style="margin-top: 8px;"><xsl:value-of select="marketplace/name"/> — Services Directory</h1>
                        <p style="color: #475569; font-weight: 600;"><xsl:value-of select="marketplace/description"/> · <xsl:value-of select="marketplace/city"/>, <xsl:value-of select="marketplace/country"/></p>
                    </div>
                    <div>
                        <span class="badge" style="background: #10B981; color: #fff;">DTD Validated: XML Specification Compliant</span>
                    </div>
                </div>

                <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; margin-bottom: 16px;">Browse Categories</h2>
                <div class="cat-grid">
                    <xsl:for-each select="categories/category">
                        <div class="cat-card">
                            <div class="icon"><xsl:value-of select="icon"/></div>
                            <h3><xsl:value-of select="cat_name"/></h3>
                            <p style="font-size: 0.8rem; color: #64748B; margin-top: 4px;"><xsl:value-of select="short_desc"/></p>
                        </div>
                    </xsl:for-each>
                </div>

                <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; margin-bottom: 16px;">Service Providers (<xsl:value-of select="count(providers/provider)"/> Active Pros)</h2>
                <div class="pro-grid">
                    <xsl:for-each select="providers/provider">
                        <div class="pro-card">
                            <div>
                                <div class="pro-head">
                                    <div>
                                        <div class="pro-name"><xsl:value-of select="provider_name"/></div>
                                        <div style="font-size: 0.85rem; color: #64748B; font-weight: 600;">📍 <xsl:value-of select="area"/>, <xsl:value-of select="city"/></div>
                                    </div>
                                    <div class="tag"><xsl:value-of select="service_type"/></div>
                                </div>
                                <div class="meta">
                                    <span><span class="star">★</span> <xsl:value-of select="rating"/> (<xsl:value-of select="review_count"/> reviews)</span>
                                    <span>⏱ <xsl:value-of select="experience"/> Yrs Exp</span>
                                    <span>💰 Starts ₹<xsl:value-of select="hourly_rate"/></span>
                                </div>
                                <p class="bio"><xsl:value-of select="bio"/></p>
                                
                                <div class="offerings-box">
                                    <h4>Listed Offerings &amp; Rates</h4>
                                    <xsl:for-each select="offerings/item">
                                        <div class="service-row">
                                            <span><xsl:value-of select="item_title"/></span>
                                            <span class="service-price">₹<xsl:value-of select="item_price"/></span>
                                        </div>
                                    </xsl:for-each>
                                </div>
                            </div>
                            <a class="book-btn">
                                <xsl:attribute name="href">../../index.php?page=book&amp;provider_id=<xsl:value-of select="id"/></xsl:attribute>
                                Book This Pro ⚡
                            </a>
                        </div>
                    </xsl:for-each>
                </div>
            </div>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
