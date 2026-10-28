"""
ISMS Compliance Assessment Platform — Enterprise Presentation Generator
Theme: Vanta Cyber-Compliance Dark (Indigo + Emerald + Slate Obsidian)
Run: python generate_isms_ppt.py
"""

import os
import sys
from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN, MSO_ANCHOR
from pptx.enum.shapes import MSO_SHAPE
from pptx.oxml import parse_xml

# Fix Windows cp1252 console encoding crash in VS Code Code Runner
if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")
if hasattr(sys.stderr, "reconfigure"):
    sys.stderr.reconfigure(encoding="utf-8", errors="replace")

# ==============================================================================
# LOCKED GUIDE & STUDENT DETAILS (PROFESSIONAL MATRIX)
# ==============================================================================
STUDENT_NAME    = "Kaswan Sangeeta Bhimsingh"
ROLL_NO         = "17"
COLLEGE         = "Silver Oak College of Computer Application"
GUIDE_NAME      = "Prof. Harsh Barot"
DATE            = "29-10-2026"
LIVE_URL        = "https://ismsaudit.infinityfree.io"
GITHUB_URL      = "https://github.com/sangeetakaswan-dev/isms-tool"
SCREENSHOTS_DIR = os.path.join("docs", "screenshots")
LOGO_REL_PATH   = os.path.join("public", "images", "logo-icon.png")
OUTPUT_FILE     = "ISMS_ISO27001_Compliance_Presentation.pptx"
TOTAL_SLIDES    = 16

# ==============================================================================
# THEME TOKENS: "Vanta Cyber-Compliance Dark" (App-Aligned & Professional)
# ==============================================================================
BG_OBSIDIAN   = RGBColor(11, 15, 25)     # #0B0F19 Deep Obsidian Navy
CARD_BG       = RGBColor(22, 31, 51)     # #161F33 Dark Glass Card
CARD_ALT      = RGBColor(30, 41, 59)     # #1E293B Slate Card
INSIGHT_BG    = RGBColor(23, 37, 84)     # #172554 Deep Royal Insight Box
BORDER_SLATE  = RGBColor(51, 65, 85)     # #334155 Subtle Border
ACCENT_INDIGO = RGBColor(99, 102, 241)   # #6366F1 Primary App Indigo
ACCENT_VIOLET = RGBColor(139, 149, 246)  # #8B95F6 Secondary Glow
SUCCESS       = RGBColor(16, 185, 129)   # #10B981 Compliant Emerald Green
WARNING       = RGBColor(245, 158, 11)   # #F59E0B Maturity / Audit Gold
DANGER        = RGBColor(239, 68, 68)    # #EF4444 Critical Risk / 403 Rose
CYAN_GLOW     = RGBColor(6, 182, 212)    # #06B6D4 Architecture & Multi-Tenancy Cyan
TEXT_WHITE    = RGBColor(248, 250, 252)  # #F8FAFC Crisp White
TEXT_MUTED    = RGBColor(148, 163, 184)  # #94A3B8 Muted Silver
TEXT_LO       = RGBColor(107, 114, 128)  # #6B7280 Footers

FONT_HEADING = "Segoe UI"
FONT_BODY    = "Segoe UI"
FONT_MONO    = "Consolas"

SW = Inches(13.333)
SH = Inches(7.5)

# ==============================================================================
# STEP 0: LOGO & SCREENSHOT PATH RESOLVERS
# ==============================================================================
def resolve_logo_path():
    """
    Resolves public/images/logo-icon.png from current or parent project directories.
    """
    candidates = [
        LOGO_REL_PATH,
        os.path.join("..", LOGO_REL_PATH),
        os.path.join("images", "logo-icon.png"),
        os.path.join(SCREENSHOTS_DIR, "logo-icon.png"),
        "logo-icon.png"
    ]
    for path in candidates:
        if os.path.exists(path):
            print(f"[INFO] Using Brand Logo: {os.path.abspath(path)}")
            return path
    print(f"[WARN] Logo not found at '{LOGO_REL_PATH}'. Placeholder badge will be used.")
    return None

def get_screenshot_path(rel_path):
    candidates = [
        os.path.join(SCREENSHOTS_DIR, rel_path),
        os.path.join("..", SCREENSHOTS_DIR, rel_path),
        rel_path
    ]
    for path in candidates:
        if os.path.exists(path):
            return path
    return None

# ==============================================================================
# CORE MORPH & DESIGN ENGINE
# ==============================================================================
def enable_morph_transition(slide):
    try:
        transition_xml = parse_xml(
            '<p:transition xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main" spd="med" advClick="1">'
            '  <p:extLst>'
            '    <p:ext uri="{D9489498-53F2-484D-A90E-23E2B614D64A}">'
            '      <p159:morph xmlns:p159="http://schemas.microsoft.com/office/powerpoint/2015/09/main" option="byObject"/>'
            '    </p:ext>'
            '  </p:extLst>'
            '</p:transition>'
        )
        sld = slide._element
        insert_idx = 1
        for i, child in enumerate(sld):
            if child.tag.endswith('cSld') or child.tag.endswith('clrMapOvr'):
                insert_idx = i + 1
        sld.insert(insert_idx, transition_xml)
    except Exception:
        pass

def create_slide(prs, blank_layout):
    s = prs.slides.add_slide(blank_layout)
    s.background.fill.solid()
    s.background.fill.fore_color.rgb = BG_OBSIDIAN
    enable_morph_transition(s)
    return s

def add_morph_progress_and_nodes(slide, step_index, hero_img_path=None, hero_pos=None):
    # 1. Animated Top Progress Track + Moving Pill
    track = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.55), Inches(0.22), Inches(12.233), Inches(0.05))
    track.fill.solid()
    track.fill.fore_color.rgb = BORDER_SLATE
    track.line.fill.background()

    pill_left = 0.55 + (step_index - 1) * (11.35 / float(TOTAL_SLIDES - 1))
    pill = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(pill_left), Inches(0.17), Inches(0.88), Inches(0.15))
    pill.name = "!!progress_pill"
    pill.fill.solid()
    pill.fill.fore_color.rgb = SUCCESS if step_index % 2 == 1 else ACCENT_INDIGO
    pill.line.fill.background()

    # 2. Floating Morphing Compliance Status Bars on Margins
    bar_configs = [
        ("!!node_1", 12.92, 1.4 + (step_index % 3) * 0.55, 0.12, 0.80, SUCCESS),
        ("!!node_2", 13.10, 2.3 - (step_index % 2) * 0.45, 0.12, 0.60, ACCENT_INDIGO),
        ("!!node_3", 0.16,  5.1 - (step_index % 3) * 0.65, 0.12, 0.85, WARNING),
    ]
    for name, l, t, w, h, col in bar_configs:
        c = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(l), Inches(t), Inches(w), Inches(h))
        c.name = name
        c.fill.solid()
        c.fill.fore_color.rgb = col
        c.line.color.rgb = col

    # 3. Morphing Brand Logo Visual (public/images/logo-icon.png)
    if hero_pos:
        hl, ht, hw, hh = hero_pos
        if hero_img_path and os.path.exists(hero_img_path):
            pic = slide.shapes.add_picture(hero_img_path, Inches(hl), Inches(ht), width=Inches(hw), height=Inches(hh))
            pic.name = "!!hero_3d_visual"
        else:
            fallback = add_rect(slide, hl, ht, hw, hh, fill=CARD_ALT, line=CYAN_GLOW, line_w=1.5, rounded=True, name="!!hero_3d_visual")
            fallback.text_frame.text = "ISMS\nLOGO"
            p = fallback.text_frame.paragraphs[0]
            p.font.size = Pt(10)
            p.font.bold = True
            p.font.color.rgb = CYAN_GLOW
            p.alignment = PP_ALIGN.CENTER

def add_rect(slide, left, top, width, height, fill=None, line=None, line_w=1.5, rounded=True, radius=0.04, name=None):
    shp_type = MSO_SHAPE.ROUNDED_RECTANGLE if rounded else MSO_SHAPE.RECTANGLE
    shp = slide.shapes.add_shape(shp_type, Inches(left), Inches(top), Inches(width), Inches(height))
    if name:
        shp.name = name
    if fill is None:
        shp.fill.background()
    else:
        shp.fill.solid()
        shp.fill.fore_color.rgb = fill
    if line is None:
        shp.line.fill.background()
    else:
        shp.line.color.rgb = line
        shp.line.width = Pt(line_w)
    shp.shadow.inherit = False
    if rounded and shp.adjustments:
        try:
            shp.adjustments[0] = radius
        except Exception:
            pass
    return shp

def add_card(slide, left, top, width, height, border_color=BORDER_SLATE, fill_color=CARD_BG, name=None):
    return add_rect(slide, left, top, width, height, fill=fill_color, line=border_color, line_w=1.75, rounded=True, radius=0.04, name=name)

def add_text(slide, text, left, top, width, height, size=14, color=TEXT_WHITE, bold=False, align=PP_ALIGN.LEFT, name=None, font=FONT_BODY):
    tb = slide.shapes.add_textbox(Inches(left), Inches(top), Inches(width), Inches(height))
    if name:
        tb.name = name
    tf = tb.text_frame
    tf.word_wrap = True
    tf.margin_left = tf.margin_right = Inches(0.05)
    tf.margin_top = tf.margin_bottom = Inches(0.03)
    p = tf.paragraphs[0]
    p.text = text
    p.font.name = FONT_HEADING if bold and font == FONT_BODY else font
    p.font.size = Pt(size)
    p.font.bold = bold
    p.font.color.rgb = color
    p.alignment = align
    return tb

def add_bullets(slide, items, left, top, width, height, size=11.5, color=TEXT_WHITE, spacing=5.5):
    tb = slide.shapes.add_textbox(Inches(left), Inches(top), Inches(width), Inches(height))
    tf = tb.text_frame
    tf.word_wrap = True
    for i, item in enumerate(items):
        p = tf.paragraphs[0] if i == 0 else tf.add_paragraph()
        p.space_after = Pt(spacing)
        if isinstance(item, tuple):
            prefix, rest = item[0], item[1]
            p_color = item[2] if len(item) > 2 else SUCCESS
            r1 = p.add_run()
            r1.text = prefix
            r1.font.name = FONT_BODY
            r1.font.size = Pt(size)
            r1.font.bold = True
            r1.font.color.rgb = p_color

            r2 = p.add_run()
            r2.text = rest
            r2.font.name = FONT_BODY
            r2.font.size = Pt(size)
            r2.font.bold = False
            r2.font.color.rgb = color
        else:
            p.text = item
            p.font.name = FONT_BODY
            p.font.size = Pt(size)
            p.font.color.rgb = color
    return tb

def feature_card(slide, left, top, width, height, icon, title, lines, accent=ACCENT_INDIGO, card_name=None):
    add_rect(slide, left, top, width, height, fill=CARD_BG, rounded=True, radius=0.06, line=BORDER_SLATE, line_w=1.5, name=card_name)
    add_rect(slide, left + 0.15, top, width - 0.3, 0.05, fill=accent, line=None, rounded=False)

    chip = add_rect(slide, left + 0.22, top + 0.20, 0.48, 0.48, fill=accent, line=None, rounded=True, radius=0.22)
    chip.text_frame.text = icon
    p = chip.text_frame.paragraphs[0]
    p.font.size = Pt(15)
    p.font.color.rgb = BG_OBSIDIAN
    p.alignment = PP_ALIGN.CENTER
    chip.text_frame.vertical_anchor = MSO_ANCHOR.MIDDLE

    add_text(slide, title, left + 0.80, top + 0.24, width - 0.95, 0.40, size=13.5, bold=True, color=TEXT_WHITE)

    tb = slide.shapes.add_textbox(Inches(left + 0.22), Inches(top + 0.82), Inches(width - 0.44), Inches(height - 0.92))
    tf = tb.text_frame
    tf.word_wrap = True
    tf.margin_left = tf.margin_top = 0
    for i, line in enumerate(lines):
        p = tf.paragraphs[0] if i == 0 else tf.add_paragraph()
        if isinstance(line, tuple):
            r1 = p.add_run()
            r1.text = "• " + line[0]
            r1.font.size = Pt(10.5)
            r1.font.bold = True
            r1.font.color.rgb = line[2] if len(line) > 2 else accent
            r1.font.name = FONT_BODY

            r2 = p.add_run()
            r2.text = line[1]
            r2.font.size = Pt(10.5)
            r2.font.color.rgb = TEXT_WHITE
            r2.font.name = FONT_BODY
        else:
            p.text = "• " + line
            p.font.size = Pt(10.5)
            p.font.color.rgb = TEXT_MUTED
            p.font.name = FONT_BODY
        p.space_after = Pt(4.5)

def add_header(slide, phase_badge, title_text, objective_text, badge_color=SUCCESS):
    add_card(slide, 0.55, 0.40, 2.75, 0.38, border_color=badge_color, fill_color=CARD_ALT, name="!!unit_badge")
    add_text(slide, phase_badge, 0.60, 0.43, 2.65, 0.32, size=10.2, color=badge_color, bold=True, align=PP_ALIGN.CENTER)

    add_text(slide, title_text, 3.42, 0.36, 8.45, 0.42, size=19.5, color=TEXT_WHITE, bold=True, name="!!slide_title")
    add_text(slide, objective_text, 3.42, 0.76, 8.45, 0.32, size=11.0, color=CYAN_GLOW, bold=True)

def add_insight_box(slide, label_title, insight_text, top=6.05, height=0.88):
    add_card(slide, 0.55, top, 12.233, height, border_color=CYAN_GLOW, fill_color=INSIGHT_BG, name="!!insight_strip")
    add_bullets(slide, [
        (f"💡 {label_title}:  ", insight_text, WARNING)
    ], 0.75, top + 0.08, 11.85, height - 0.12, size=11.0, color=TEXT_WHITE, spacing=2)

def add_footer(slide, current_slide, total_slides=TOTAL_SLIDES):
    add_rect(slide, 0.55, 7.04, 12.233, 0.02, fill=BORDER_SLATE, line=None, rounded=False)
    footer_left = (
        f"Student: {STUDENT_NAME} (Roll No: {ROLL_NO})  |  "
        f"Guide: {GUIDE_NAME}  |  {COLLEGE}  |  Laravel 11 SaaS"
    )
    add_text(slide, footer_left, 0.55, 7.09, 10.5, 0.28, size=9.0, color=TEXT_LO)
    add_text(slide, f"Slide {current_slide} / {total_slides}", 11.1, 7.09, 1.68, 0.28, size=9.5, color=ACCENT_VIOLET, bold=True, align=PP_ALIGN.RIGHT)

def add_plot_frame(slide, rel_filename, caption, left, top, width, height, border_color=CYAN_GLOW, frame_name=None):
    add_card(slide, left, top, width, height, border_color=border_color, fill_color=CARD_BG, name=frame_name)
    img_path = get_screenshot_path(rel_filename)
    pad = 0.10
    img_w = width - (2 * pad)
    img_h = height - 0.42

    if img_path:
        slide.shapes.add_picture(img_path, Inches(left + pad), Inches(top + pad), width=Inches(img_w), height=Inches(img_h))
    else:
        add_card(slide, left + pad, top + pad, img_w, img_h, border_color=WARNING, fill_color=BG_OBSIDIAN)
        add_text(
            slide,
            f"[ Screenshot: docs/screenshots/{rel_filename} ]",
            left + pad + 0.15, top + (img_h / 2) - 0.2, img_w - 0.3, 0.5,
            size=10.5, color=WARNING, bold=True, align=PP_ALIGN.CENTER
        )
    add_text(slide, caption, left + 0.12, top + height - 0.31, width - 0.24, 0.26, size=9.8, color=TEXT_MUTED, bold=True, align=PP_ALIGN.CENTER)

def add_notes(slide, notes_text):
    slide.notes_slide.notes_text_frame.text = notes_text

def style_table(table, col_widths, font_size=9.8):
    for idx, w in enumerate(col_widths):
        table.columns[idx].width = Inches(w)
    for r_idx, row in enumerate(table.rows):
        for c_idx, cell in enumerate(row.cells):
            cell.vertical_anchor = MSO_ANCHOR.MIDDLE
            cell.fill.solid()
            if r_idx == 0:
                cell.fill.fore_color.rgb = CARD_ALT
            elif r_idx % 2 == 1:
                cell.fill.fore_color.rgb = CARD_BG
            else:
                cell.fill.fore_color.rgb = BG_OBSIDIAN
            for p in cell.text_frame.paragraphs:
                p.font.name = FONT_BODY
                p.font.size = Pt(font_size if r_idx > 0 else font_size + 0.5)
                p.font.bold = (r_idx == 0)
                p.font.color.rgb = WARNING if r_idx == 0 else TEXT_WHITE
                p.alignment = PP_ALIGN.LEFT if c_idx <= 1 else PP_ALIGN.CENTER


# ==============================================================================
# BUILD 16-SLIDE ENTERPRISE ISMS PRESENTATION
# ==============================================================================
def build_presentation():
    hero_img = resolve_logo_path()

    prs = Presentation()
    prs.slide_width = SW
    prs.slide_height = SH
    blank_layout = prs.slide_layouts[6]

    # --------------------------------------------------------------------------
    # SLIDE 1: HERO TITLE SLIDE
    # --------------------------------------------------------------------------
    s1 = create_slide(prs, blank_layout)
    add_card(s1, 0.55, 0.52, 12.233, 5.75, border_color=ACCENT_INDIGO, fill_color=CARD_BG, name="!!focus_card")

    add_card(s1, 0.90, 0.82, 7.60, 0.42, border_color=WARNING, fill_color=CARD_ALT, name="!!unit_badge")
    add_text(
        s1,
        "Information Security Management System Audit",
        0.95, 0.87, 7.50, 0.32, size=10.2, color=WARNING, bold=True, align=PP_ALIGN.CENTER
    )

    add_text(s1, "ISO/IEC 27001:2022 COMPLIANCE", 0.90, 1.38, 7.8, 0.58, size=30, color=TEXT_WHITE, bold=True, name="!!slide_title")
    add_text(s1, "Enterprise Multi-Tenant ISMS Assessment & Risk SaaS Platform", 0.90, 1.98, 7.8, 0.40, size=16.5, color=SUCCESS, bold=True)
    add_text(
        s1,
        "An 8-Phase Enterprise GRC Application Built on Laravel 11, Livewire 3 & Tailwind CSS, "
        "Featuring 115 Seeded ISO Controls, 5x5 Risk Heatmap, Auto-SoA Versioning, and 71 Verified Automated Tests.",
        0.90, 2.45, 7.6, 0.72, size=12.0, color=TEXT_MUTED
    )

    add_card(s1, 8.75, 0.82, 3.70, 2.85, border_color=CYAN_GLOW, fill_color=BG_OBSIDIAN)
    add_morph_progress_and_nodes(s1, step_index=1, hero_img_path=hero_img, hero_pos=(9.55, 0.98, 2.10, 2.10))
    add_text(s1, "ISMS Audit Platform Brand Identity", 8.85, 3.25, 3.50, 0.28, size=9.8, color=CYAN_GLOW, bold=True, align=PP_ALIGN.CENTER)

    add_card(s1, 0.90, 3.82, 11.55, 2.15, border_color=BORDER_SLATE, fill_color=CARD_ALT)
    add_bullets(s1, [
        ("Student Name & Roll No: ", f"{STUDENT_NAME}   |   Roll Number: {ROLL_NO}   |   {COLLEGE}", SUCCESS),
        ("Affiliated Institution: ", f"{COLLEGE}   |   Gujarat, India", WARNING),
        ("Project Guide & Date: ", f"{GUIDE_NAME}   |   Submission Date: {DATE}", SUCCESS),
        ("Live Production & GitHub: ", f"{LIVE_URL}   |   {GITHUB_URL}", CYAN_GLOW),
        ("Enterprise Tech Stack: ", "Laravel 11.x  •  PHP 8.2+  •  MySQL 8.0  •  Livewire 3  •  Tailwind CSS  •  Chart.js 4  •  DomPDF & Maatwebsite Excel", ACCENT_VIOLET),
    ], 1.10, 3.94, 11.15, 1.95, size=11.5, spacing=5)

    add_card(s1, 0.55, 6.40, 12.233, 0.48, border_color=BORDER_SLATE, fill_color=CARD_ALT)
    add_text(
        s1,
        "ARCHITECTURE:  [P1: Multi-Tenancy] • [P2: Assessment Engine] • [P3/P7: 5x5 Risk & Treatments] • [P4: SoA Engine] • [P5: Document DMS] • [P6: Analytics] • [P8: UI & Security]",
        0.65, 6.48, 12.0, 0.30, size=9.2, color=SUCCESS, bold=True, align=PP_ALIGN.CENTER
    )
    add_footer(s1, 1)
    add_notes(s1, f"Good morning Sir/Ma'am. My name is {STUDENT_NAME} (Roll No {ROLL_NO}). Under the guidance of {GUIDE_NAME}, I am presenting our enterprise ISO/IEC 27001:2022 Compliance Assessment SaaS Platform built using Laravel 11.")

    # --------------------------------------------------------------------------
    # SLIDE 2: 8-PHASE DISTRIBUTED DEVELOPMENT MATRIX
    # --------------------------------------------------------------------------
    s2 = create_slide(prs, blank_layout)
    add_header(s2, "PROJECT ROADMAP", "8-Phase Agile Engineering & Deliverables Matrix", "Structured Multi-Phase Execution from Multi-Tenant Core to Live Production Deployment", badge_color=ACCENT_INDIGO)
    add_morph_progress_and_nodes(s2, step_index=2, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_card(s2, 0.52, 1.20, 12.29, 4.72, border_color=ACCENT_INDIGO, fill_color=CARD_BG, name="!!focus_card")
    t2_shape = s2.shapes.add_table(5, 4, Inches(0.55), Inches(1.24), Inches(12.233), Inches(4.64))
    t2 = t2_shape.table
    headers2 = ["Development Phases", "Core Engineering Objective", "Key Technical Modules Implemented", "Verified Deliverables & Tests"]
    rows2 = [
        [
            "PHASE 1 & PHASE 2:\nMulti-Tenancy Core &\nAssessment Engine",
            "Establish tenant-isolated architecture, ISO 27001 library, and assessment lifecycle.",
            "• Single-DB multi-tenancy via TenantScope & tenant_user RBAC pivot\n• Seeded 11 Domains & 115 Controls (Main Clauses 4–10 + 93 Annex A)\n• Assessment CRUD, team assignment & 0–5 Maturity scoring\n• Weighted ComplianceCalculator (Partial compliance = 50% weight)",
            "✔ 37/37 Tests Passing\n✔ 115 ISO Controls Seeded\n✔ Service-Repo-DTO Layer\n✔ Token Invitations"
        ],
        [
            "PHASE 3 & PHASE 4:\nAsset Register, Risk &\nStatement of Applicability",
            "Quantify asset criticality, evaluate risks, and generate formal ISO 27001 SoA.",
            "• Information Asset Register with Confidentiality, Integrity, Availability (1–5)\n• Automated StatementOfApplicabilityService mapped from responses\n• Single & Bulk SoA entry approval workflow with user/timestamp\n• JSON data_snapshot versioning (soa_versions) & side-by-side comparison",
            "✔ 54/54 Tests Passing\n✔ Auto-SoA Justification\n✔ Version Diff Engine\n✔ SoA PDF/Excel Exports"
        ],
        [
            "PHASE 5 & PHASE 6:\nDocument DMS &\nReporting Analytics",
            "Manage versioned compliance policies and executive multi-format reporting.",
            "• Document Management System (DMS) with v1.0 ➔ v1.1 version history\n• Multi-reviewer approval workflow & document_control_map linking\n• Strategy Pattern ReportRegistry (6 Report Types) + CSV/PDF/XLSX exports\n• Daily report_snapshots table for 12-month historical compliance trend",
            "✔ 63/63 Tests Passing\n✔ 6 Strategy Report Classes\n✔ 5 Chart.js Visualizations\n✔ Export Audit Trail"
        ],
        [
            "PHASE 7 & PHASE 8:\nRisk Register, Jobs,\nUI Overhaul & Go-Live",
            "Upgrade risk treatment workflows, automate jobs, harden RBAC, and deploy live.",
            "• Dedicated Risk model (1–25 score), 4 Treatment strategies & 5x5 Heatmap\n• Email + In-App Notifications & 3 Scheduled Jobs (Snapshots, Reminders)\n• Vanta-style Tailwind UI overhaul, AssetPolicy/RiskPolicy RBAC (403 guard)\n• Custom error pages (403, 404, 419, 500, 503) & InfinityFree deployment",
            "✔ 71/71 Tests (169 Asserts)\n✔ 25+ Blade Views Polished\n✔ 15+ Production Bug Fixes\n✔ Live HTTPS Deployment"
        ]
    ]
    for c_i, h in enumerate(headers2):
        t2.cell(0, c_i).text = h
    for r_i, r_data in enumerate(rows2, start=1):
        for c_i, val in enumerate(r_data):
            t2.cell(r_i, c_i).text = val
    style_table(t2, [2.25, 2.45, 5.233, 2.30], font_size=9.6)
    for r_i in range(1, 5):
        t2.cell(r_i, 0).text_frame.paragraphs[0].font.color.rgb = SUCCESS
        t2.cell(r_i, 0).text_frame.paragraphs[0].font.bold = True
        t2.cell(r_i, 2).text_frame.paragraphs[0].alignment = PP_ALIGN.LEFT
        t2.cell(r_i, 3).text_frame.paragraphs[0].alignment = PP_ALIGN.LEFT
        t2.cell(r_i, 3).text_frame.paragraphs[0].font.color.rgb = CYAN_GLOW

    add_insight_box(
        s2,
        "STRICT ARCHITECTURAL GOVERNANCE ACROSS ALL 8 PHASES",
        "Zero business logic resides in Controllers or Blade views. Every module strictly follows Request Validation ➔ Readonly DTO ➔ Service Class ➔ Repository Interface ➔ Tenant-Scoped Eloquent Model."
    )
    add_footer(s2, 2)
    add_notes(s2, "This matrix summarizes our 8-phase engineering journey, progressing from multi-tenant foundations and ISO 27001 assessment workflows to risk management, SoA generation, document control, analytics, and live deployment.")

    # --------------------------------------------------------------------------
    # SLIDE 3: PHASE 1 — MULTI-TENANCY CORE & CLEAN ARCHITECTURE
    # --------------------------------------------------------------------------
    s3 = create_slide(prs, blank_layout)
    add_header(s3, "PHASE 1 • FOUNDATION", "Phase 1: Multi-Tenancy Core, RBAC & Clean Architecture", "Objective: Establish automatic tenant data isolation and enterprise Service-Repository-DTO patterns", badge_color=CYAN_GLOW)
    add_morph_progress_and_nodes(s3, step_index=3, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    feature_card(s3, 0.55, 1.25, 3.90, 4.60, "🏛️", "Service-Repo-DTO Pattern", [
        ("Thin Controllers: ", "Only validate FormRequests, invoke Services, and return views/JSON.", SUCCESS),
        ("Service Layer: ", "All domain rules isolated in app/Services/ (Tenant, Assessment, Risk, SoA).", CYAN_GLOW),
        ("Repository Contracts: ", "Interfaces bound in AppServiceProvider for clean testability.", WARNING),
        ("Readonly DTOs: ", "Type-safe data transfer across layers (TenantData, AssessmentData, RiskData).", ACCENT_VIOLET)
    ], CYAN_GLOW, card_name="!!focus_card")

    feature_card(s3, 4.70, 1.25, 3.90, 4.60, "🏢", "Single-DB Multi-Tenancy", [
        ("Automatic TenantScope: ", "Global Eloquent scope appends WHERE tenant_id = current_tenant_id.", SUCCESS),
        ("Column-Safe Guard: ", "Checks Schema::hasColumn('tenant_id') to protect global ISO catalogs.", CYAN_GLOW),
        ("Session Context: ", "EnsureTenantSelected middleware enforces active organization context.", WARNING),
        ("Zero Data Leakage: ", "Verified via automated cross-tenant isolation feature tests.", DANGER)
    ], SUCCESS, card_name="!!secondary_card")

    feature_card(s3, 8.85, 1.25, 3.93, 4.60, "🔐", "RBAC & Token Invitations", [
        ("5 Granular Roles: ", "Owner, Admin, Auditor, Assessor, and Viewer via tenant_user pivot.", ACCENT_INDIGO),
        ("Secure Onboarding: ", "Tokenized email invitations with 7-day expiry and role pre-assignment.", SUCCESS),
        ("Policy Enforcement: ", "Role-based checks via roleInCurrentTenant() and Laravel Policies.", WARNING),
        ("Complete ISO Seeders: ", "11 Domains + 115 Controls seeded with implementation guidance.", CYAN_GLOW)
    ], ACCENT_INDIGO)

    add_insight_box(
        s3,
        "GLOBAL CATALOGUES vs TENANT-SCOPED ENTITIES",
        "ISO 27001 Domains (11) and Controls (115) are stored as global read-only reference tables, while Assessments, Risks, Assets, Documents, and Snapshots are strictly isolated per organization via TenantScope."
    )
    add_footer(s3, 3)
    add_notes(s3, "In Phase 1, we established our single-database, shared-schema multi-tenant architecture. Using a Global TenantScope, every query automatically filters by the authenticated user's current_tenant_id.")

    # --------------------------------------------------------------------------
    # SLIDE 4: PHASE 1 & 2 — ISO 27001 LIBRARY & ASSESSMENT ENGINE
    # --------------------------------------------------------------------------
    s4 = create_slide(prs, blank_layout)
    add_header(s4, "PHASE 2 • ASSESSMENT ENGINE", "Phase 2: ISO/IEC 27001:2022 Library & Assessment Workflow", "Objective: Evaluate 115 controls across 11 domains with 0–5 maturity scoring and weighted compliance math", badge_color=SUCCESS)
    add_morph_progress_and_nodes(s4, step_index=4, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_card(s4, 0.55, 1.22, 5.95, 4.68, border_color=SUCCESS, fill_color=CARD_BG, name="!!focus_card")
    add_text(s4, "COMPLETE ISO/IEC 27001:2022 CONTROL LIBRARY", 0.78, 1.35, 5.5, 0.35, size=13.5, color=SUCCESS, bold=True)
    add_bullets(s4, [
        ("11 Structured Domains: ", "7 Main Clauses (Clauses 4 to 10 — ISMS Management) + 4 Annex A Themes (A.5 to A.8).", CYAN_GLOW),
        ("Annex A Theme Breakdown (93 Core + Extended = 115 Total): ", "\n   • A.5 Organizational Controls: 37 core controls (Policies, Roles, Threat Intel)\n   • A.6 People Controls: 8 controls (Screening, NDA, Awareness, Remote Work)\n   • A.7 Physical Controls: 14 controls (Perimeters, Cabling, Clear Desk, Media)\n   • A.8 Technological Controls: 34 core controls (Crypto, Access, Logging, SDLC)", TEXT_WHITE),
        ("Assessment Lifecycle: ", "Draft ➔ In Progress ➔ Completed ➔ Archived with team member role assignments (assessment_team table).", WARNING),
        ("Bulk & Granular Evaluation: ", "Single control evaluation or bulk status updates across domains with evidence notes and gap descriptions.", SUCCESS)
    ], 0.78, 1.75, 5.5, 4.0, size=11.0, spacing=5.5)

    add_card(s4, 6.75, 1.22, 6.033, 4.68, border_color=WARNING, fill_color=CARD_BG, name="!!secondary_card")
    add_text(s4, "MATURITY MODEL (0–5) & COMPLIANCE CALCULATOR", 6.98, 1.35, 5.6, 0.35, size=13.5, color=WARNING, bold=True)
    add_bullets(s4, [
        ("5 Compliance States: ", "✅ Compliant (100% weight)  |  ⚠️ Partially Compliant (50% weight)  |  ❌ Non-Compliant (0%)  |  ⊘ Not Applicable (Excluded from denominator)  |  ○ Not Assessed.", SUCCESS),
        ("CMMI-Aligned Maturity Scale (Levels 0 to 5): ", "\n   • Level 0: Not Implemented   |   Level 1: Initial / Ad-hoc\n   • Level 2: Repeatable        |   Level 3: Defined & Documented\n   • Level 4: Managed & Measured|   Level 5: Optimized & Continuous", CYAN_GLOW),
        ("Weighted Compliance Formula (ComplianceCalculator.php): ", "\n   Score = [(Compliant + 0.5 × Partially_Compliant) / Applicable_Assessed] × 100", WARNING),
        ("Real-Time Domain Analytics: ", "Automatically computes domain-level completion percentages, maturity distribution, and top compliance gaps.", ACCENT_VIOLET)
    ], 6.98, 1.75, 5.6, 4.0, size=11.0, spacing=5.5)

    add_insight_box(
        s4,
        "ACCURATE DENOMINATOR HANDLING FOR 'NOT APPLICABLE' CONTROLS",
        "Controls marked 'Not Applicable' require a mandatory justification and are excluded from the compliance score denominator so organizations are not penalized for out-of-scope controls."
    )
    add_footer(s4, 4)
    add_notes(s4, "Phase 2 implements the complete assessment lifecycle. Our ComplianceCalculator uses a weighted formula where Compliant equals 1.0, Partially Compliant equals 0.5, and Not Applicable controls are excluded from the denominator.")

    # --------------------------------------------------------------------------
    # SLIDE 5: PHASE 3 & 7 — ASSET CMDB, 5x5 RISK REGISTER & TREATMENTS
    # --------------------------------------------------------------------------
    s5 = create_slide(prs, blank_layout)
    add_header(s5, "PHASE 3 & 7 • RISK ENGINE", "Phase 3 & 7: Asset CMDB, 5×5 Risk Register & Treatment Plans", "Objective: Quantify asset criticality, automate Likelihood × Impact risk scoring, and track mitigations", badge_color=DANGER)
    add_morph_progress_and_nodes(s5, step_index=5, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    feature_card(s5, 0.55, 1.25, 3.90, 4.60, "💻", "Information Asset CMDB", [
        ("6 Asset Categories: ", "Hardware, Software, Data, People, Facility, and Cloud Services.", CYAN_GLOW),
        ("CIA Triad Valuation: ", "Confidentiality (1–5), Integrity (1–5), and Availability (1–5) ratings.", SUCCESS),
        ("Criticality Detection: ", "Automatically flags critical assets where any CIA dimension >= 4.", WARNING),
        ("Risk Linkage: ", "Every identified risk links directly to an underlying information asset.", ACCENT_VIOLET)
    ], CYAN_GLOW, card_name="!!secondary_card")

    feature_card(s5, 4.70, 1.25, 3.90, 4.60, "🔥", "5×5 Quantitative Risk Matrix", [
        ("Automated Risk Score: ", "Likelihood (1–5) × Impact (1–5) = Score (1 to 25).", DANGER),
        ("4 Severity Bands: ", "1–4 Low (Green) | 5–9 Medium (Yellow) | 10–16 High (Orange) | 17–25 Critical (Red).", WARNING),
        ("5-Stage Risk Lifecycle: ", "Identified ➔ Assessed ➔ Treated ➔ Monitored ➔ Closed.", SUCCESS),
        ("Interactive Heatmap: ", "JSON endpoint (/risks/heatmap/data) powers a clickable 5×5 drill-down grid.", CYAN_GLOW)
    ], DANGER, card_name="!!focus_card")

    feature_card(s5, 8.85, 1.25, 3.93, 4.60, "🛡️", "4-Strategy Treatment Plans", [
        ("ISO 27001 Strategies: ", "Mitigate, Accept, Transfer, or Avoid with cost estimates & due dates.", SUCCESS),
        ("Automated Transitions: ", "Adding/completing a plan transitions risk from Assessed ➔ Treated ➔ Monitored.", CYAN_GLOW),
        ("Proactive Alerts: ", "Dispatches RiskAssignedNotification and daily overdue treatment reminders.", WARNING),
        ("Route Namespace Fix: ", "Cleanly scoped under risks.treatments.* to eliminate route collisions.", ACCENT_INDIGO)
    ], SUCCESS)

    add_insight_box(
        s5,
        "CLOSED-LOOP RISK LIFECYCLE AUTOMATION",
        "Creating a treatment plan automatically advances the parent risk status, notifies the assigned risk owner via database and email channels, and feeds directly into the Phase 6 Executive Risk Register report."
    )
    add_footer(s5, 5)
    add_notes(s5, "Across Phases 3 and 7, we built the Asset Register and the quantitative 5x5 Risk Register. Risk scores are automatically computed from Likelihood times Impact, and linked to four ISO treatment strategies: Mitigate, Accept, Transfer, and Avoid.")

    # --------------------------------------------------------------------------
    # SLIDE 6: PHASE 4 & 5 — STATEMENT OF APPLICABILITY (SoA) & DOCUMENT DMS
    # --------------------------------------------------------------------------
    s6 = create_slide(prs, blank_layout)
    add_header(s6, "PHASE 4 & 5 • SoA & DMS", "Phase 4 & 5: Statement of Applicability (SoA) & Document DMS", "Objective: Automate Clause 6.1.3(d) SoA generation with version snapshots and controlled policy lifecycle", badge_color=WARNING)
    add_morph_progress_and_nodes(s6, step_index=6, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_card(s6, 0.55, 1.22, 5.95, 4.68, border_color=WARNING, fill_color=CARD_BG, name="!!focus_card")
    add_text(s6, "PHASE 4: STATEMENT OF APPLICABILITY (SoA) ENGINE", 0.78, 1.35, 5.5, 0.35, size=13.5, color=WARNING, bold=True)
    add_bullets(s6, [
        ("One-Click SoA Generation: ", "StatementOfApplicabilityService maps all assessment responses into formal soa_entries.", SUCCESS),
        ("Applicability & Exclusion Rationale: ", "Auto-generates justification text and captures mandatory exclusion_reason for non-applicable controls.", CYAN_GLOW),
        ("Single & Bulk Approval Workflow: ", "Authorized leads can approve individual control entries or bulk-approve the entire SoA with audit timestamps.", WARNING),
        ("Immutable Version Snapshots (soa_versions): ", "Stores complete JSON data_snapshot payloads (e.g., v1.0, v2.0) and provides a side-by-side Version Comparison diff view.", ACCENT_VIOLET),
        ("Audit-Ready Exports: ", "Generates formatted SoA PDF documents (DomPDF) and multi-sheet Excel workbooks (Maatwebsite).", TEXT_WHITE)
    ], 0.78, 1.75, 5.5, 4.0, size=11.0, spacing=5.5)

    add_card(s6, 6.75, 1.22, 6.033, 4.68, border_color=CYAN_GLOW, fill_color=CARD_BG, name="!!secondary_card")
    add_text(s6, "PHASE 5: DOCUMENT MANAGEMENT SYSTEM (DMS)", 6.98, 1.35, 5.6, 0.35, size=13.5, color=CYAN_GLOW, bold=True)
    add_bullets(s6, [
        ("Centralized ISMS Repository: ", "Manages Policies, Procedures, Records, Templates, and Evidence across tenant-isolated directories.", SUCCESS),
        ("Semantic Version Control (document_versions): ", "Tracks full file history (v1.0 ➔ v1.1 ➔ v2.0) with change logs and creator attribution.", CYAN_GLOW),
        ("Peer Review & Approval Workflow: ", "Draft ➔ Under Review ➔ Approved ➔ Archived lifecycle via document_reviews (Approve, Reject, Request Changes).", WARNING),
        ("Control Mapping (document_control_map): ", "Many-to-many pivot linking approved policies directly to ISO 27001 controls as audit evidence.", ACCENT_INDIGO),
        ("Pre-Seeded Policy Library: ", "Includes 10 core templates (InfoSec Policy, Access Control, Incident Response, BCP, Backup Policy).", TEXT_WHITE)
    ], 6.98, 1.75, 5.6, 4.0, size=11.0, spacing=5.5)

    add_insight_box(
        s6,
        "ISO 27001 CLAUSE 6.1.3(d) & CLAUSE 7.5 COMPLIANCE",
        "The SoA module fulfills Clause 6.1.3(d) by documenting control applicability and justifications, while the DMS satisfies Clause 7.5 (Documented Information) through strict version history and approval records."
    )
    add_footer(s6, 6)
    add_notes(s6, "Phase 4 and Phase 5 address two mandatory audit artifacts required by external ISO 27001 auditors: the Statement of Applicability with JSON snapshot versioning, and the Document Management System with version control and control mapping.")

    # --------------------------------------------------------------------------
    # SLIDE 7: PHASE 6 & 7 — EXECUTIVE ANALYTICS, STRATEGY REPORTS & JOBS
    # --------------------------------------------------------------------------
    s7 = create_slide(prs, blank_layout)
    add_header(s7, "PHASE 6 & 7 • ANALYTICS", "Phase 6 & 7: Executive Analytics, Strategy Pattern Reports & Jobs", "Objective: Deliver real-time Chart.js telemetry, extensible multi-format reporting, and automated background jobs", badge_color=ACCENT_INDIGO)
    add_morph_progress_and_nodes(s7, step_index=7, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    feature_card(s7, 0.55, 1.25, 3.90, 4.60, "📊", "Executive Chart.js Telemetry", [
        ("5 Live Chart.js Visuals: ", "Domain Compliance Bar, Maturity Pie, Risk Doughnut, Document Doughnut & 12-Month Trend Line.", SUCCESS),
        ("JSON Metrics Endpoint: ", "Asynchronous /dashboard/metrics endpoint powered by DashboardService.", CYAN_GLOW),
        ("Daily Compliance Snapshots: ", "report_snapshots table enforces unique(tenant_id, report_type, snapshot_date).", WARNING),
        ("Defensive Repository: ", "ReportRepository gracefully handles missing optional models without crashing.", ACCENT_VIOLET)
    ], ACCENT_INDIGO, card_name="!!secondary_card")

    feature_card(s7, 4.70, 1.25, 3.90, 4.60, "📑", "Strategy Pattern Reports", [
        ("ReportRegistry Engine: ", "Clean Strategy Pattern implementing ReportInterface & AbstractReport.", SUCCESS),
        ("6 Enterprise Report Types: ", "Executive Summary, Gap Analysis, Risk Register, SoA Status, Document Compliance & Asset Inventory.", CYAN_GLOW),
        ("3 Export Formats: ", "Native Streaming CSV, Branded DomPDF, and Maatwebsite XLSX.", WARNING),
        ("Export Audit Log: ", "Every generated report is recorded in generated_reports with user & timestamp.", ACCENT_INDIGO)
    ], SUCCESS, card_name="!!focus_card")

    feature_card(s7, 8.85, 1.25, 3.93, 4.60, "⏱️", "Scheduled Jobs & Alerts", [
        ("SnapshotComplianceScoreJob: ", "Runs daily at 02:00 to record tenant compliance posture.", CYAN_GLOW),
        ("SendTreatmentDueRemindersJob: ", "Runs daily at 08:00 to alert owners of upcoming/overdue risk plans.", DANGER),
        ("PruneReportSnapshotsJob: ", "Runs weekly on Sundays to enforce a clean 24-month retention window.", WARNING),
        ("In-App Notification Center: ", "Full /notifications UI with unread counters, mark-read, and mark-all-read.", SUCCESS)
    ], CYAN_GLOW)

    add_insight_box(
        s7,
        "OPEN-CLOSED PRINCIPLE VIA REPORT REGISTRY (STRATEGY PATTERN)",
        "Adding a new compliance report requires zero modifications to ReportController or ExportService—developers simply create a class extending AbstractReport and register its key in ReportRegistry."
    )
    add_footer(s7, 7)
    add_notes(s7, "In Phase 6 and 7, we implemented our reporting engine using the Strategy Pattern. Six distinct report classes plug into a central ReportRegistry and export seamlessly to CSV, PDF, and Excel.")

    # --------------------------------------------------------------------------
    # SLIDE 8: PHASE 8 — ENTERPRISE UI/UX OVERHAUL & SECURITY HARDENING
    # --------------------------------------------------------------------------
    s8 = create_slide(prs, blank_layout)
    add_header(s8, "PHASE 8 • UI & SECURITY", "Phase 8: Enterprise UI/UX Overhaul, Security Hardening & Go-Live", "Objective: Transform UI into a Vanta/Drata-grade SaaS, enforce strict Policy RBAC, and deploy to live HTTPS", badge_color=SUCCESS)
    add_morph_progress_and_nodes(s8, step_index=8, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    feature_card(s8, 0.55, 1.25, 3.90, 4.60, "🎨", "Vanta/Drata SaaS UI System", [
        ("25+ Views Redesigned: ", "Modern Indigo (#4F46E5) & Slate design system with Inter + JetBrains Mono.", ACCENT_INDIGO),
        ("Split-Screen Auth & Dark Mode: ", "Glassmorphism login/register with twinkling stars and Light/Dark toggle.", CYAN_GLOW),
        ("Responsive Navigation: ", "Collapsible desktop sidebar and mobile drawer menu with user topbar.", SUCCESS),
        ("Bento Grid & Data Tables: ", "Color-coded status badges, hover actions, and sectioned forms.", WARNING)
    ], ACCENT_INDIGO, card_name="!!secondary_card")

    feature_card(s8, 4.70, 1.25, 3.90, 4.60, "🛡️", "RBAC & Security Hardening", [
        ("Policy Authorization: ", "AssetPolicy & RiskPolicy block 'viewer' users from mutating data (403 Forbidden).", DANGER),
        ("Route Guard Audit: ", "Moved /reports and /soa routes inside auth + tenant.selected middleware.", SUCCESS),
        ("IDOR Prevention: ", "Cross-tenant direct URL tampering strictly returns 404/403.", WARNING),
        ("POST Logout & CSRF: ", "Converted GET logout links into @csrf POST forms to prevent CSRF logout traps.", CYAN_GLOW)
    ], DANGER, card_name="!!focus_card")

    feature_card(s8, 8.85, 1.25, 3.93, 4.60, "🚀", "Custom Errors & Live Deploy", [
        ("5 Branded Error Pages: ", "Custom Tailwind views for 403, 404, 419 (CSRF Expired), 500, and 503.", WARNING),
        ("Rich Demo Seeders: ", "AssessmentSeeder (115 responses), SoASeeder, DocumentSeeder & NotificationSeeder.", SUCCESS),
        ("Production Vite Build: ", "Compiled assets via npm run build and removed public/hot artifact.", CYAN_GLOW),
        ("InfinityFree Deployment: ", "Restructured htdocs/ entry point live at ismsaudit.infinityfree.io.", ACCENT_VIOLET)
    ], SUCCESS)

    add_insight_box(
        s8,
        "DEFENSE-IN-DEPTH AUTHORIZATION (MIDDLEWARE + POLICIES + TENANT SCOPE)",
        "Every request passes through three independent security gates: Route Middleware verifies authentication and tenant context, Policies enforce role permissions (Viewer vs Assessor/Admin), and TenantScope isolates database rows."
    )
    add_footer(s8, 8)
    add_notes(s8, "In Phase 8, we overhauled the entire UI to match modern B2B SaaS platforms like Vanta and Drata, enforced strict AssetPolicy and RiskPolicy authorization so Viewers get a 403 on write actions, and deployed live.")

    # --------------------------------------------------------------------------
    # SLIDE 9: LIVE APP DEMO — LANDING PAGE, DARK AUTH & EXECUTIVE DASHBOARD
    # --------------------------------------------------------------------------
    s9 = create_slide(prs, blank_layout)
    add_header(s9, "LIVE DEMO • UI & TELEMETRY", "Live Prototype: Split-Screen Dark Auth & Executive Dashboard", "Visual verification of Phase 8 glassmorphism authentication and Phase 6 Chart.js telemetry", badge_color=ACCENT_INDIGO)
    add_morph_progress_and_nodes(s9, step_index=9, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_plot_frame(s9, "auth/03_login_dark.png", "Split-Screen Dark Mode Login with Glassmorphism & Brand Identity", 0.55, 1.25, 5.95, 4.65, border_color=ACCENT_VIOLET, frame_name="!!secondary_card")
    add_plot_frame(s9, "dashboard/01_dashboard_overview.png", "Executive Dashboard: 4 KPI Cards, Domain Bar Chart, Risk & Maturity Breakdown", 6.80, 1.25, 5.98, 4.65, border_color=SUCCESS, frame_name="!!focus_card")

    add_insight_box(
        s9,
        "REAL-TIME COMPLIANCE POSTURE AT A GLANCE",
        "The Executive Dashboard aggregates 115 control responses, active risk severities, and document review states into unified KPI cards and responsive Chart.js canvases."
    )
    add_footer(s9, 9)
    add_notes(s9, "Here we see actual screenshots from our live build: the split-screen dark mode login interface on the left, and the Executive Dashboard with real-time Chart.js visualizations on the right.")

    # --------------------------------------------------------------------------
    # SLIDE 10: LIVE APP DEMO — CONTROL ASSESSMENT & 5x5 RISK REGISTER
    # --------------------------------------------------------------------------
    s10 = create_slide(prs, blank_layout)
    add_header(s10, "LIVE DEMO • CORE WORKFLOWS", "Live Prototype: ISO Control Evaluation & 5×5 Risk Register", "Visual verification of domain-by-domain control scoring (Phase 2) and risk treatment tracking (Phase 7)", badge_color=SUCCESS)
    add_morph_progress_and_nodes(s10, step_index=10, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_plot_frame(s10, "assessments/03_assessment_conduct.png", "Control Assessment Interface: Status Badges, 0–5 Maturity & Evidence Notes", 0.55, 1.25, 5.95, 4.65, border_color=SUCCESS, frame_name="!!focus_card")
    add_plot_frame(s10, "risks/01_risk_register_list.png", "Risk Register & 5x5 Heatmap: Likelihood × Impact Scoring & Severity Filtering", 6.80, 1.25, 5.98, 4.65, border_color=DANGER, frame_name="!!secondary_card")

    add_insight_box(
        s10,
        "SEAMLESS ASSESSOR UX & RISK TRACEABILITY",
        "Assessors evaluate controls domain-by-domain with instant progress bar updates, while risk owners filter high/critical risks directly from the interactive 5×5 severity matrix."
    )
    add_footer(s10, 10)
    add_notes(s10, "On the left is the Control Assessment interface where assessors set compliance status and maturity levels across all 115 controls. On the right is the Risk Register featuring automated Likelihood times Impact scoring.")

    # --------------------------------------------------------------------------
    # SLIDE 11: LIVE APP DEMO — STATEMENT OF APPLICABILITY & DOCUMENT DMS
    # --------------------------------------------------------------------------
    s11 = create_slide(prs, blank_layout)
    add_header(s11, "LIVE DEMO • AUDIT ARTIFACTS", "Live Prototype: Statement of Applicability (SoA) & Document DMS", "Visual verification of Clause 6.1.3(d) SoA matrix (Phase 4) and versioned policy library (Phase 5)", badge_color=WARNING)
    add_morph_progress_and_nodes(s11, step_index=11, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_plot_frame(s11, "soa/01_soa_matrix.png", "Statement of Applicability (SoA): Applicability, Justifications & Bulk Approval", 0.55, 1.25, 5.95, 4.65, border_color=WARNING, frame_name="!!focus_card")
    add_plot_frame(s11, "documents/01_document_list.png", "Document Management System (DMS): Semantic Versions & Control Mapping", 6.80, 1.25, 5.98, 4.65, border_color=CYAN_GLOW, frame_name="!!secondary_card")

    add_insight_box(
        s11,
        "ONE-CLICK AUDITOR EVIDENCE GENERATION",
        "Both the Statement of Applicability matrix and the Document Compliance inventory can be exported on-demand to branded PDFs or multi-sheet Excel workbooks for external certification audits."
    )
    add_footer(s11, 11)
    add_notes(s11, "These screenshots highlight our Statement of Applicability matrix with approval badges and version comparison, alongside our Document Management System tracking policy versions and review states.")

    # --------------------------------------------------------------------------
    # SLIDE 12: LIVE APP DEMO — BENTO REPORTS, RBAC 403 & MOBILE RESPONSIVENESS
    # --------------------------------------------------------------------------
    s12 = create_slide(prs, blank_layout)
    add_header(s12, "LIVE DEMO • REPORTS & RBAC", "Live Prototype: Reports Bento Grid, 403 RBAC Guard & Mobile UI", "Visual proof of multi-format report center, Viewer role authorization block, and mobile drawer responsiveness", badge_color=DANGER)
    add_morph_progress_and_nodes(s12, step_index=12, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_plot_frame(s12, "reports/01_reports_bento_grid.png", "Reports Bento Grid: 6 Report Types (CSV / PDF / XLSX)", 0.55, 1.25, 3.90, 4.65, border_color=ACCENT_INDIGO, frame_name="!!secondary_card")
    add_plot_frame(s12, "security/01_viewer_role_403.png", "Security Proof: Custom 403 Access Denied for Viewer Role", 4.70, 1.25, 3.90, 4.65, border_color=DANGER, frame_name="!!focus_card")
    add_plot_frame(s12, "mobile/01_mobile_dashboard.png", "Mobile Responsive Viewport & Drawer Navigation", 8.85, 1.25, 3.93, 4.65, border_color=SUCCESS)

    add_insight_box(
        s12,
        "VERIFIED ROLE-BASED ACCESS CONTROL & RESPONSIVE ERGONOMICS",
        "When a user with the 'viewer' role attempts to create or modify an Asset or Risk, AssetPolicy and RiskPolicy intercept the request and render the custom 403 Access Denied view."
    )
    add_footer(s12, 12)
    add_notes(s12, "Here we demonstrate three critical Phase 6 and Phase 8 deliverables: the 6-report Bento Grid export center, the custom 403 Access Denied page blocking unauthorized Viewer mutations, and mobile responsiveness.")

    # --------------------------------------------------------------------------
    # SLIDE 13: AUTOMATED TESTING & VERIFICATION MATRIX (71 TESTS PASSING)
    # --------------------------------------------------------------------------
    s13 = create_slide(prs, blank_layout)
    add_header(s13, "QUALITY ASSURANCE", "Comprehensive Automated Testing Matrix (71/71 Passing)", "100% Pass Rate Across 71 PHPUnit Feature & Unit Tests (169 Assertions Verified)", badge_color=SUCCESS)
    add_morph_progress_and_nodes(s13, step_index=13, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    add_card(s13, 0.52, 1.20, 12.29, 4.72, border_color=SUCCESS, fill_color=CARD_BG, name="!!focus_card")
    t13_shape = s13.shapes.add_table(9, 5, Inches(0.55), Inches(1.24), Inches(12.233), Inches(4.64))
    t13 = t13_shape.table
    headers13 = ["Phase / Module Suite", "Test Classes Executed", "Key Verification Scenarios Covered", "Tests / Asserts", "Status"]
    rows13 = [
        ["Phase 1: Auth & Profile", "AuthenticationTest, RegistrationTest, ProfileTest, PasswordResetTest", "Breeze login, registration, email verification, password reset & profile updates", "25 Tests / 58 Asserts", "100% PASS ✔"],
        ["Phase 1: Multi-Tenancy", "TenantCreationTest, UserInvitationTest, ExampleTest", "Organization onboarding, token invitation acceptance, role assignment & tenant isolation", "4 Tests / 15 Asserts", "100% PASS ✔"],
        ["Phase 2: Assessments", "AssessmentCreationTest, ProgressCalculationTest, ResponseUpdateTest", "Assessment wizard CRUD, 115 control initialization, single/bulk updates & weighted score", "8 Tests / 20 Asserts", "100% PASS ✔"],
        ["Phase 3: Asset & Risk Core", "AssetCRUDTest, RiskScoreCalculationTest, TreatmentPlanTest", "Asset CIA valuation, Likelihood × Impact (1–25) calculation & severity band mapping", "12 Tests / 19 Asserts", "100% PASS ✔"],
        ["Phase 4: SoA Engine", "SoAGenerationTest, ApprovalWorkflowTest, SoAExportTest, VersionComparisonTest", "Auto-SoA generation from responses, single/bulk approval, JSON version diffing & exports", "5 Tests / 16 Asserts", "100% PASS ✔"],
        ["Phase 5: Document DMS", "DocumentUploadTest, VersionControlTest, ReviewWorkflowTest", "Tenant-isolated storage, semantic version history, reviewer approval & control mapping", "3 Tests / 7 Asserts", "100% PASS ✔"],
        ["Phase 6: Reporting & Charts", "DashboardTest, ReportGenerationTest", "Executive dashboard KPI metrics, null-tenant 403 guard, and CSV/PDF/XLSX report exports", "6 Tests / 15 Asserts", "100% PASS ✔"],
        ["Phase 7: Risk v2 & Jobs", "RiskRegisterTest, RiskTreatmentWorkflowTest, RiskHeatmapTest, RiskNotificationTest, ScheduledSnapshotTest", "Dedicated Risk CRUD, 4 treatment strategies, 5x5 heatmap JSON, notifications & daily snapshot job", "8 Tests / 21 Asserts", "100% PASS ✔"]
    ]
    for c_i, h in enumerate(headers13):
        t13.cell(0, c_i).text = h
    for r_i, r_data in enumerate(rows13, start=1):
        for c_i, val in enumerate(r_data):
            t13.cell(r_i, c_i).text = val
    style_table(t13, [2.15, 3.25, 4.183, 1.45, 1.20], font_size=9.3)
    for r_i in range(1, 9):
        t13.cell(r_i, 0).text_frame.paragraphs[0].font.color.rgb = CYAN_GLOW
        t13.cell(r_i, 0).text_frame.paragraphs[0].font.bold = True
        t13.cell(r_i, 2).text_frame.paragraphs[0].alignment = PP_ALIGN.LEFT
        t13.cell(r_i, 3).text_frame.paragraphs[0].font.color.rgb = WARNING
        t13.cell(r_i, 4).text_frame.paragraphs[0].font.color.rgb = SUCCESS
        t13.cell(r_i, 4).text_frame.paragraphs[0].font.bold = True

    add_insight_box(
        s13,
        "CONTINUOUS REGRESSION SAFETY ACROSS 8 PHASES",
        "Every new phase was validated against the entire cumulative PHPUnit test suite using RefreshDatabase and model factories, ensuring zero regressions from Phase 1 (29 tests) to Phase 7/8 (71 tests, 169 assertions)."
    )
    add_footer(s13, 13)
    add_notes(s13, "Quality assurance was enforced at every phase handoff. Our test suite grew from 29 tests in Phase 1 to 71 passing tests and 169 assertions by Phase 7, verifying tenant isolation, scoring math, and exports.")

    # --------------------------------------------------------------------------
    # SLIDE 14: REAL-WORLD TECHNICAL CHALLENGES & SOLUTIONS
    # --------------------------------------------------------------------------
    s14 = create_slide(prs, blank_layout)
    add_header(s14, "RETROSPECTIVE", "Engineering Challenges & Production Bug Resolutions", "Real-world architectural hurdles encountered across Phases 1–8 and how they were resolved", badge_color=WARNING)
    add_morph_progress_and_nodes(s14, step_index=14, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    challenges = [
        ("HTML Form Nesting Bug (Update Triggering Delete)", "Placing the Delete <form> inside the Update <form> caused browser submit events to delete records on edit. Resolved by moving the delete form outside and binding the button via HTML5 form='delete-form'."),
        ("Global TenantScope on Child & Catalogue Tables", "Applying TenantScope to AssessmentResponse and ISO Control tables caused SQL column errors because they lack direct tenant_id columns. Resolved by adding a Schema::hasColumn('tenant_id') guard and scoping via parent Assessment."),
        ("Phase 3 vs Phase 7 Route & Foreign Key Collision", "Extending risk_treatment_plans for the dedicated Risk model conflicted with Phase 3 treatments.store routes and NOT NULL FKs. Resolved by making risk_assessment_id nullable and namespacing Phase 7 routes under risks.treatments.*."),
        ("Asset Owner String vs Eloquent Relationship Crash", "Calling Asset::with('owner') on the Executive Dashboard threw a relation exception because assets.owner stores a string name. Resolved by removing invalid eager loading in ReportRepository and defensive model checks."),
        ("Shared Hosting (InfinityFree) Vite & Document Root", "Standard Laravel public/ folder structure and lingering public/hot files caused 404 asset errors in production. Resolved by shifting public entry points into htdocs/, compiling production Vite bundles, and removing hot file.")
    ]
    y = 1.25
    for title, desc in challenges:
        add_card(s14, 0.55, y, 12.233, 0.86, border_color=BORDER_SLATE, fill_color=CARD_BG)
        add_rect(s14, 0.55, y, 0.09, 0.86, fill=WARNING, line=None, rounded=False)
        add_text(s14, title, 0.82, y + 0.08, 3.85, 0.70, size=11.0, bold=True, color=WARNING)
        add_text(s14, desc, 4.78, y + 0.08, 7.85, 0.70, size=10.2, color=TEXT_WHITE)
        y += 0.94

    add_insight_box(
        s14,
        "PRODUCTION DEBUGGING & DEFENSIVE ENGINEERING",
        "Resolving these 15+ cross-phase integration bugs—ranging from HTML5 form attribute binding to defensive repository queries—ensured a rock-solid production deployment."
    )
    add_footer(s14, 14)
    add_notes(s14, "During development and Phase 8 polishing, we diagnosed and fixed several critical engineering bugs, including nested HTML form deletion issues, TenantScope column checks, and route name collisions.")

    # --------------------------------------------------------------------------
    # SLIDE 15: ARCHITECTURAL LESSONS & PHASE 9 FUTURE ROADMAP
    # --------------------------------------------------------------------------
    s15 = create_slide(prs, blank_layout)
    add_header(s15, "FUTURE ROADMAP", "Architectural Lessons & Phase 9 Roadmap (REST API & Cloud)", "Next-generation enterprise capabilities ready to plug into our existing Service & DTO architecture", badge_color=CYAN_GLOW)
    add_morph_progress_and_nodes(s15, step_index=15, hero_img_path=hero_img, hero_pos=(12.00, 0.36, 0.72, 0.72))

    feature_card(s15, 0.55, 1.25, 3.90, 4.60, "🔌", "REST API v1 (Laravel Sanctum)", [
        ("Zero Business Logic Rewrite: ", "Thin /api/v1/ controllers will directly invoke existing Service & DTO classes.", SUCCESS),
        ("Sanctum Token Auth: ", "Scoped API tokens per tenant with configurable rate limiting.", CYAN_GLOW),
        ("JSON API Resources: ", "Standardized AssessmentResource, RiskResource, and SoAResource payloads.", WARNING),
        ("OpenAPI / Swagger Docs: ", "Complete Postman collection and interactive Swagger specification.", ACCENT_VIOLET)
    ], CYAN_GLOW, card_name="!!focus_card")

    feature_card(s15, 4.70, 1.25, 3.90, 4.60, "🕵️", "Granular Audit Trail & Security", [
        ("Owen-it Auditing UI: ", "Visual diff viewer showing old_values vs new_values for every control & risk change.", SUCCESS),
        ("Multi-Factor Auth (MFA): ", "TOTP authenticator app enforcement for Owner and Admin roles.", DANGER),
        ("Enterprise SSO: ", "SAML 2.0 and OAuth2 (Microsoft Entra ID / Google Workspace) login integration.", WARNING),
        ("Unified Risk Model Cleanup: ", "Deprecating legacy Phase 3 risk table in favor of the Phase 7 Risk engine.", CYAN_GLOW)
    ], ACCENT_INDIGO, card_name="!!secondary_card")

    feature_card(s15, 8.85, 1.25, 3.93, 4.60, "☁️", "Cloud Telemetry & Automation", [
        ("Automated Evidence Collection: ", "AWS IAM, GitHub & Azure connectors to auto-verify Annex A.8 technical controls.", SUCCESS),
        ("Web-Cron & Queue Workers: ", "Dedicated Redis + Supervisor workers and token-authenticated web cron triggers.", CYAN_GLOW),
        ("Slack & MS Teams Webhooks: ", "Real-time channel alerts when critical risks or overdue treatments occur.", WARNING),
        ("AI Control Copilot: ", "LLM-assisted gap remediation advice and policy draft generation.", ACCENT_VIOLET)
    ], SUCCESS)

    add_insight_box(
        s15,
        "WHY CLEAN ARCHITECTURE PAYS OFF IN PHASE 9",
        "Because 100% of our compliance scoring, SoA generation, and risk calculations already live in Service classes accepting DTOs, building the Phase 9 REST API requires only thin API controllers and Sanctum middleware."
    )
    add_footer(s15, 15)
    add_notes(s15, "Looking ahead to Phase 9, our strict Service-Repository-DTO architecture makes exposing a full Laravel Sanctum REST API, visual audit trail UI, and automated cloud evidence integrations effortless.")

    # --------------------------------------------------------------------------
    # SLIDE 16: CONCLUSION, LIVE DEMO LINKS & Q&A
    # --------------------------------------------------------------------------
    s16 = create_slide(prs, blank_layout)
    add_card(s16, 0.55, 0.52, 12.233, 6.22, border_color=SUCCESS, fill_color=CARD_BG, name="!!focus_card")

    add_card(s16, 0.90, 0.84, 6.40, 0.42, border_color=CYAN_GLOW, fill_color=CARD_ALT, name="!!unit_badge")
    add_text(s16, "ENTERPRISE CAPSTONE  •  ALL 8 DEVELOPMENT PHASES COMPLETED", 0.95, 0.89, 6.30, 0.32, size=10.8, color=CYAN_GLOW, bold=True, align=PP_ALIGN.CENTER)

    add_text(s16, "THANK YOU! QUESTIONS & LIVE DEMO", 0.90, 1.42, 7.8, 0.58, size=28, color=TEXT_WHITE, bold=True, name="!!slide_title")
    add_text(s16, f"Live Production URL: {LIVE_URL}", 0.90, 2.02, 7.8, 0.36, size=14.5, color=SUCCESS, bold=True)
    add_text(s16, f"GitHub Repository: {GITHUB_URL}", 0.90, 2.40, 7.8, 0.36, size=13.0, color=CYAN_GLOW, bold=True)
    add_text(s16, "Demo Credentials:  owner@demo.com  |  assessor@demo.com  |  viewer@demo.com  (Password: password)", 0.90, 2.82, 7.8, 0.36, size=11.0, color=WARNING, bold=True)

    add_card(s16, 9.05, 0.84, 3.40, 2.65, border_color=CYAN_GLOW, fill_color=BG_OBSIDIAN)
    add_morph_progress_and_nodes(s16, step_index=16, hero_img_path=hero_img, hero_pos=(9.70, 0.98, 2.10, 2.10))
    add_text(s16, "ISMS Audit Platform Brand Identity", 9.15, 3.16, 3.20, 0.26, size=9.5, color=CYAN_GLOW, bold=True, align=PP_ALIGN.CENTER)

    badges = [
        ("PHASE 1–2 • CORE", "Multi-Tenant RBAC\n115 ISO Controls & Scoring", CYAN_GLOW),
        ("PHASE 3 & 7 • RISK", "Asset CMDB + 5×5 Matrix\n4 Treatment Strategies", DANGER),
        ("PHASE 4–5 • AUDIT", "Auto-SoA + Version Diff\nDocument DMS & Mapping", WARNING),
        ("PHASE 6 & 8 • LIVE", "6 Strategy Reports + Charts\n71 Tests + Vanta UI Live", SUCCESS)
    ]
    for i, (b_top, b_sub, col) in enumerate(badges):
        bx = 0.90 + i * 2.92
        add_card(s16, bx, 3.68, 2.72, 1.42, border_color=col, fill_color=CARD_ALT)
        add_text(s16, b_top, bx + 0.08, 3.78, 2.56, 0.35, size=12.5, color=col, bold=True, align=PP_ALIGN.CENTER)
        add_text(s16, b_sub, bx + 0.08, 4.18, 2.56, 0.78, size=10.2, color=TEXT_WHITE, align=PP_ALIGN.CENTER)

    add_card(s16, 0.90, 5.26, 11.55, 1.28, border_color=BORDER_SLATE, fill_color=CARD_ALT)
    add_text(
        s16,
        "✔ Phase 1 to Phase 8 Reports Complete     |     ✔ 71/71 PHPUnit Tests (169 Assertions)     |     ✔ Live HTTPS Production Deployment\n"
        f"Student: {STUDENT_NAME} (Roll No: {ROLL_NO})   •   Guide: {GUIDE_NAME}   •   {COLLEGE}\n"
        "Department of Computer Applications",
        1.05, 5.38, 11.25, 1.05, size=11.2, color=ACCENT_VIOLET, bold=True, align=PP_ALIGN.CENTER
    )

    add_footer(s16, 16)
    add_notes(s16, "Thank you Sir/Ma'am. The platform is live at ismsaudit.infinityfree.io and all 71 automated tests are passing. I am now ready for your questions and a live demonstration.")

    prs.save(OUTPUT_FILE)
    print("=" * 78)
    print(f"[OK] ENTERPRISE ISMS PRESENTATION GENERATED: {os.path.abspath(OUTPUT_FILE)}")
    print(f"   * Total Slides : {TOTAL_SLIDES} (Zero duplicate blank slides)")
    print(f"   * Student      : {STUDENT_NAME} (Roll No: {ROLL_NO})")
    print(f"   * Guide        : {GUIDE_NAME}")
    print(f"   * Live URL     : {LIVE_URL}")
    print(f"   * GitHub URL   : {GITHUB_URL}")
    print(f"   * Brand Logo   : {LOGO_REL_PATH}")
    print("=" * 78)

if __name__ == "__main__":
    build_presentation()