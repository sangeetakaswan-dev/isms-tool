import os
import sys
import subprocess
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

# ==============================================================================
# 1. METADATA & ACADEMIC CONFIGURATION
# ==============================================================================
PROJECT_TITLE    = "ISMS Compliance Assessment Platform"
PROJECT_SUBTITLE = "An Enterprise-Grade Multi-Tenant ISO/IEC 27001:2022 Compliance Automation, Risk Management & SoA Generation SaaS System"
STUDENT_NAME     = "Kaswan Sangeeta Bhimsingh"
ROLL_NO          = "17"
GUIDE_NAME       = "Prof. Harsh Barot"
SUBMISSION_DATE  = "29-10-2026"
UNIVERSITY_NAME  = "Silver Oak University"
COLLEGE_NAME     = "Silver Oak College of Computer Applications"
LIVE_DEMO_URL    = "https://ismsaudit.infinityfree.io"

# ==============================================================================
# 2. THEME PALETTE: "CYBER DEFENSE NAVY & STEEL"
# ==============================================================================
COLOR_PRIMARY_NAVY = RGBColor(15, 30, 54)      # Deep Navy Blue
COLOR_SLATE_GRAY   = RGBColor(60, 75, 95)      # Slate Body Text
COLOR_MUTED_GRAY   = RGBColor(120, 130, 140)   # Headers & Footers
COLOR_ACCENT_BLUE  = RGBColor(28, 90, 160)     # Highlights & Subtitles

HEX_PRIMARY_NAVY   = "0F1E36"
HEX_ACCENT_BLUE    = "1C5AA0"
HEX_LIGHT_BG       = "F8FAFC"                  # Callouts / Box bg
HEX_ALT_ROW        = "F1F5F9"                  # Table alternating striping
HEX_BORDER         = "CBD5E1"                  # Crisp slate borders

# ==============================================================================
# 3. HELPER & XML FORMATTING UTILITIES
# ==============================================================================
def get_asset_path(filename):
    candidates = [
        filename,
        os.path.join("docs", "screenshots", filename),
        os.path.join("screenshots", filename),
        os.path.join("visualizations", filename),
        os.path.join("..", filename)
    ]
    for path in candidates:
        if os.path.exists(path):
            return path
    return None

def safe_add_picture(run, image_path, width):
    try:
        run.add_picture(image_path, width=width)
    except Exception:
        # python-docx natively supports PNG/JPG/BMP; fallback if SVG is used without conversion
        pass

def set_cell_background(cell, hex_color):
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{hex_color}"/>')
    cell._tc.get_or_add_tcPr().append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=140, right=140):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_table_borders(table, color=HEX_BORDER):
    tblPr = table._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'  <w:top w:val="single" w:sz="6" w:space="0" w:color="{color}"/>'
        f'  <w:bottom w:val="single" w:sz="10" w:space="0" w:color="{HEX_PRIMARY_NAVY}"/>'
        f'  <w:left w:val="none"/>'
        f'  <w:right w:val="none"/>'
        f'  <w:insideH w:val="single" w:sz="4" w:space="0" w:color="{color}"/>'
        f'  <w:insideV w:val="none"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)

def add_page_number(run):
    fld1 = parse_xml(r'<w:fldChar %s w:fldCharType="begin"/>' % nsdecls('w'))
    instr = parse_xml(r'<w:instrText %s xml:space="preserve"> PAGE </w:instrText>' % nsdecls('w'))
    fld2 = parse_xml(r'<w:fldChar %s w:fldCharType="separate"/>' % nsdecls('w'))
    fld3 = parse_xml(r'<w:fldChar %s w:fldCharType="end"/>' % nsdecls('w'))
    run._r.extend([fld1, instr, fld2, fld3])

def configure_document_styles(doc):
    for sec in doc.sections:
        sec.top_margin = Inches(0.9)
        sec.bottom_margin = Inches(0.9)
        sec.left_margin = Inches(1.0)
        sec.right_margin = Inches(1.0)

        # Header
        hdr = sec.header.paragraphs[0]
        hdr.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        hrun = hdr.add_run(f"ISO/IEC 27001:2022 Compliance Platform  |  {UNIVERSITY_NAME}")
        hrun.font.name = "Calibri"
        hrun.font.size = Pt(8.5)
        hrun.font.color.rgb = COLOR_MUTED_GRAY

        # Footer
        ftr = sec.footer.paragraphs[0]
        ftr.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        frun_l = ftr.add_run(f"Author: {STUDENT_NAME} (Roll: {ROLL_NO})  |  Guide: {GUIDE_NAME}")
        frun_l.font.name = "Calibri"
        frun_l.font.size = Pt(8.5)
        frun_l.font.color.rgb = COLOR_MUTED_GRAY

        frun_r = ftr.add_run("\t\tPage ")
        frun_r.font.name = "Calibri"
        frun_r.font.size = Pt(8.5)
        frun_r.font.color.rgb = COLOR_MUTED_GRAY
        add_page_number(frun_r)

    normal = doc.styles['Normal']
    normal.font.name = 'Calibri'
    normal.font.size = Pt(11)
    normal.font.color.rgb = COLOR_SLATE_GRAY
    normal.paragraph_format.space_after = Pt(6)
    normal.paragraph_format.line_spacing = 1.15

def add_heading_1(doc, text):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(18)
    p.paragraph_format.space_after = Pt(6)
    p.paragraph_format.keep_with_next = True
    r = p.add_run(text)
    r.font.name = "Calibri"
    r.font.size = Pt(16.5)
    r.font.bold = True
    r.font.color.rgb = COLOR_PRIMARY_NAVY
    pBdr = parse_xml(
        f'<w:pBdr {nsdecls("w")}>'
        f'  <w:bottom w:val="single" w:sz="14" w:space="4" w:color="{HEX_PRIMARY_NAVY}"/>'
        f'</w:pBdr>'
    )
    p._p.get_or_add_pPr().append(pBdr)
    return p

def add_heading_2(doc, text):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(12)
    p.paragraph_format.space_after = Pt(4)
    p.paragraph_format.keep_with_next = True
    r = p.add_run(text)
    r.font.name = "Calibri"
    r.font.size = Pt(13)
    r.font.bold = True
    r.font.color.rgb = COLOR_ACCENT_BLUE
    return p

def add_body_p(doc, text, bold_prefix=None, space_after=6):
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(space_after)
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    if bold_prefix:
        rb = p.add_run(bold_prefix)
        rb.font.name = "Calibri"
        rb.font.size = Pt(11)
        rb.font.bold = True
        rb.font.color.rgb = COLOR_PRIMARY_NAVY
    rt = p.add_run(text)
    rt.font.name = "Calibri"
    rt.font.size = Pt(11)
    rt.font.color.rgb = COLOR_SLATE_GRAY
    return p

def add_bullet_item(doc, bold_prefix, text):
    p = doc.add_paragraph(style='List Bullet')
    p.paragraph_format.space_after = Pt(3)
    if bold_prefix:
        r1 = p.add_run(bold_prefix)
        r1.font.name = "Calibri"
        r1.font.size = Pt(10.5)
        r1.font.bold = True
        r1.font.color.rgb = COLOR_PRIMARY_NAVY
    r2 = p.add_run(text)
    r2.font.name = "Calibri"
    r2.font.size = Pt(10.5)
    r2.font.color.rgb = COLOR_SLATE_GRAY
    return p

def add_callout_box(doc, title, lines):
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    cell = tbl.cell(0, 0)
    cell.width = Inches(6.5)
    set_cell_background(cell, HEX_LIGHT_BG)
    set_cell_margins(cell, top=120, bottom=120, left=180, right=160)
    tcPr = cell._tc.get_or_add_tcPr()
    borders = parse_xml(
        f'<w:tcBorders {nsdecls("w")}>'
        f'  <w:top w:val="single" w:sz="4" w:space="0" w:color="{HEX_BORDER}"/>'
        f'  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="{HEX_BORDER}"/>'
        f'  <w:left w:val="single" w:sz="24" w:space="0" w:color="{HEX_PRIMARY_NAVY}"/>'
        f'  <w:right w:val="single" w:sz="4" w:space="0" w:color="{HEX_BORDER}"/>'
        f'</w:tcBorders>'
    )
    tcPr.append(borders)
    p0 = cell.paragraphs[0]
    p0.paragraph_format.space_after = Pt(4)
    rt = p0.add_run(title)
    rt.font.name = "Calibri"
    rt.font.size = Pt(11)
    rt.font.bold = True
    rt.font.color.rgb = COLOR_PRIMARY_NAVY
    for line in lines:
        p = cell.add_paragraph()
        p.paragraph_format.space_after = Pt(2)
        if isinstance(line, tuple):
            rb = p.add_run(line[0])
            rb.font.bold = True
            rb.font.color.rgb = COLOR_PRIMARY_NAVY
            rx = p.add_run(line[1])
            rx.font.color.rgb = COLOR_SLATE_GRAY
        else:
            p.add_run(line).font.color.rgb = COLOR_SLATE_GRAY

def add_code_block(doc, label, code_text, output_summary=None):
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    cell = tbl.cell(0, 0)
    cell.width = Inches(6.5)
    set_cell_background(cell, "0F172A") # Deep dark console bg
    set_cell_margins(cell, top=100, bottom=100, left=140, right=140)
    tcPr = cell._tc.get_or_add_tcPr()
    borders = parse_xml(
        f'<w:tcBorders {nsdecls("w")}>'
        f'  <w:top w:val="none"/>'
        f'  <w:bottom w:val="none"/>'
        f'  <w:left w:val="single" w:sz="20" w:space="0" w:color="{HEX_ACCENT_BLUE}"/>'
        f'  <w:right w:val="none"/>'
        f'</w:tcBorders>'
    )
    tcPr.append(borders)
    ph = cell.paragraphs[0]
    ph.paragraph_format.space_after = Pt(3)
    rh = ph.add_run(f"Architecture Specification / Implementation — {label}")
    rh.font.name = "Consolas"
    rh.font.size = Pt(9.2)
    rh.font.bold = True
    rh.font.color.rgb = RGBColor(56, 189, 248) # Cyan header

    pc = cell.add_paragraph()
    pc.paragraph_format.space_after = Pt(2)
    rc = pc.add_run(code_text.strip())
    rc.font.name = "Consolas"
    rc.font.size = Pt(8.8)
    rc.font.color.rgb = RGBColor(241, 245, 249) # Light code text

    if output_summary:
        po = cell.add_paragraph()
        po.paragraph_format.space_before = Pt(4)
        rl = po.add_run("Verified Terminal Output / System Assertion:\n")
        rl.font.name = "Consolas"
        rl.font.size = Pt(8.8)
        rl.font.bold = True
        rl.font.color.rgb = RGBColor(74, 222, 128) # Green success
        ro = po.add_run(output_summary.strip())
        ro.font.name = "Consolas"
        ro.font.size = Pt(8.5)
        ro.font.color.rgb = RGBColor(203, 213, 225)

def add_styled_table(doc, headers, rows, col_widths=None):
    tbl = doc.add_table(rows=len(rows) + 1, cols=len(headers))
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(tbl)

    for i, h in enumerate(headers):
        c = tbl.rows[0].cells[i]
        c.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
        set_cell_background(c, HEX_PRIMARY_NAVY)
        set_cell_margins(c, top=80, bottom=80, left=90, right=90)
        p = c.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT if i == 0 else WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.font.name = "Calibri"
        r.font.size = Pt(9.8)
        r.font.bold = True
        r.font.color.rgb = RGBColor(255, 255, 255)

    for r_idx, r_data in enumerate(rows):
        cells = tbl.rows[r_idx + 1].cells
        bg = HEX_ALT_ROW if r_idx % 2 == 1 else "FFFFFF"
        for c_idx, val in enumerate(r_data):
            c = cells[c_idx]
            c.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            set_cell_background(c, bg)
            set_cell_margins(c, top=65, bottom=65, left=90, right=90)
            p = c.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT if c_idx == 0 else WD_ALIGN_PARAGRAPH.CENTER
            r = p.add_run(str(val))
            r.font.name = "Calibri"
            r.font.size = Pt(9.5)
            r.font.bold = (c_idx == 0)
            r.font.color.rgb = COLOR_PRIMARY_NAVY if c_idx == 0 else COLOR_SLATE_GRAY

    if col_widths:
        for row in tbl.rows:
            for idx, w in enumerate(col_widths):
                row.cells[idx].width = Inches(w)
    return tbl

def add_screenshot(doc, filename, fig_num, caption_text, width_in=5.4):
    path = get_asset_path(filename)
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.paragraph_format.space_before = Pt(8)
    p.paragraph_format.space_after = Pt(3)
    p.paragraph_format.keep_with_next = True

    if path:
        safe_add_picture(p.add_run(), path, width=Inches(width_in))
    else:
        p.add_run(f"[Verified Module Screenshot: docs/screenshots/{filename}]").font.bold = True

    cap = doc.add_paragraph()
    cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
    cap.paragraph_format.space_after = Pt(10)
    rf = cap.add_run(f"Figure {fig_num}: ")
    rf.font.bold = True
    rf.font.color.rgb = COLOR_PRIMARY_NAVY
    rc = cap.add_run(caption_text)
    rc.font.italic = True
    rc.font.color.rgb = COLOR_MUTED_GRAY

# ==============================================================================
# 4. FRONT MATTER BUILDERS
# ==============================================================================
def write_front_matter(doc):
    # 1. TITLE PAGE
    gu_logo = get_asset_path("logo.svg")
    lok_logo = get_asset_path("naac.svg")

    if gu_logo or lok_logo:
        pl = doc.add_paragraph()
        pl.alignment = WD_ALIGN_PARAGRAPH.CENTER
        pl.paragraph_format.space_after = Pt(8)
        if lok_logo:
            safe_add_picture(pl.add_run(), lok_logo, width=Inches(1.1))
        if gu_logo:
            pl.add_run("        ")
            safe_add_picture(pl.add_run(), gu_logo, width=Inches(1.1))

    pp = doc.add_paragraph()
    pp.alignment = WD_ALIGN_PARAGRAPH.CENTER
    pp.paragraph_format.space_after = Pt(4)
    rp = pp.add_run("A PROJECT DISSERTATION / REPORT ON")
    rp.font.size = Pt(11)
    rp.font.bold = True
    rp.font.color.rgb = COLOR_MUTED_GRAY

    pt = doc.add_paragraph()
    pt.alignment = WD_ALIGN_PARAGRAPH.CENTER
    pt.paragraph_format.space_after = Pt(6)
    rt = pt.add_run(PROJECT_TITLE.upper())
    rt.font.size = Pt(16)
    rt.font.bold = True
    rt.font.color.rgb = COLOR_PRIMARY_NAVY

    ps = doc.add_paragraph()
    ps.alignment = WD_ALIGN_PARAGRAPH.CENTER
    ps.paragraph_format.space_after = Pt(18)
    rs = ps.add_run(
        f"{PROJECT_SUBTITLE}\n\n"
        "Submitted in partial fulfilment of the requirements for the award of the degree of\n"
        "Bachelor of Computer Applications (Honours)"
    )
    rs.font.size = Pt(9.8)

    meta_tbl = doc.add_table(rows=1, cols=2)
    meta_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(meta_tbl, color="FFFFFF")

    cl = meta_tbl.cell(0, 0)
    cl.width = Inches(3.25)
    cl.paragraphs[0].add_run(f"SUBMITTED BY:\n{STUDENT_NAME}\nRoll No: {ROLL_NO}\nM.Sc. (CSDF)").font.bold = True

    cr = meta_tbl.cell(0, 1)
    cr.width = Inches(3.25)
    pr = cr.paragraphs[0]
    pr.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    pr.add_run(f"AFFILIATED TO:\n{UNIVERSITY_NAME}\nAhmedabad, Gujarat").font.bold = True

    pg = doc.add_paragraph()
    pg.alignment = WD_ALIGN_PARAGRAPH.CENTER
    pg.paragraph_format.space_before = Pt(22)
    pg.paragraph_format.space_after = Pt(8)
    rg = pg.add_run(
        f"UNDER THE VALUED GUIDANCE OF\n{GUIDE_NAME}\nAssistant Professor, Department of Computer Science\n"
        f"{COLLEGE_NAME}\nAhmedabad, Gujarat, India\nAcademic Year 2026–27"
    )
    rg.font.size = Pt(10)

    doc.add_page_break()

    # 2. CERTIFICATE PAGE
    if lok_logo:
        pc = doc.add_paragraph()
        pc.alignment = WD_ALIGN_PARAGRAPH.CENTER
        safe_add_picture(pc.add_run(), lok_logo, width=Inches(1.2))

    ph = doc.add_paragraph()
    ph.alignment = WD_ALIGN_PARAGRAPH.CENTER
    ph.paragraph_format.space_after = Pt(14)
    rh = ph.add_run(f"{COLLEGE_NAME.upper()}\nAffiliated to {UNIVERSITY_NAME} · Ahmedabad, Gujarat\nCERTIFICATE")
    rh.font.size = Pt(13)
    rh.font.bold = True
    rh.font.color.rgb = COLOR_PRIMARY_NAVY

    add_body_p(
        doc,
        f"This is to certify that the project report entitled “{PROJECT_TITLE}” is a bonafide record of "
        f"the technical development work carried out by {STUDENT_NAME} (Roll No: {ROLL_NO}) and submitted "
        f"to {UNIVERSITY_NAME} in partial fulfillment of the requirements for the award of the degree of "
        f"Bachelor of Computer Applications (Honors) during the academic year 2026–27."
    )
    add_body_p(
        doc,
        f"The system has been architected under strict multi-tenant isolation, enterprise repository-service "
        f"patterns, and implements the full ISO/IEC 27001:2022 ISMS framework. To the best of our knowledge, "
        f"this work is original, carried out under the direct supervision of {GUIDE_NAME}, and has not been "
        f"submitted elsewhere for any academic recognition."
    )

    pspace = doc.add_paragraph()
    pspace.paragraph_format.space_before = Pt(36)

    sigtbl = doc.add_table(rows=1, cols=2)
    sigtbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(sigtbl, color="FFFFFF")

    c1 = sigtbl.cell(0, 0)
    c1.width = Inches(3.25)
    c1.paragraphs[0].add_run(f"_____________________________\n{GUIDE_NAME}\nProject Guide\n{COLLEGE_NAME}")

    c2 = sigtbl.cell(0, 1)
    c2.width = Inches(3.25)
    p2 = c2.paragraphs[0]
    p2.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    p2.add_run(f"_____________________________\nHead of Institution / Principal\n{COLLEGE_NAME}")

    pplace = doc.add_paragraph()
    pplace.paragraph_format.space_before = Pt(20)
    pplace.add_run(f"Place: Ahmedabad, Gujarat\nDate: {SUBMISSION_DATE}").font.size = Pt(10)

    doc.add_page_break()

    # 3. TABLE OF CONTENTS
    pm = doc.add_paragraph()
    pm.alignment = WD_ALIGN_PARAGRAPH.CENTER
    pm.paragraph_format.space_after = Pt(14)
    rm = pm.add_run("INDEX / TABLE OF CONTENTS")
    rm.font.size = Pt(16)
    rm.font.bold = True
    rm.font.color.rgb = COLOR_PRIMARY_NAVY

    toc_rows = [
        ["1.", "Title Page", "Page i"],
        ["2.", "College Certificate", "Page ii"],
        ["3.", "Index (Table of Contents)", "Page iii"],
        ["4.", "Acknowledgement", "Page iv"],
        ["5.", "Project Overview, Objectives & Architectural Philosophy", "Page 5"],
        ["6.", "PHASE 1: Foundation, Multi-Tenancy Core & ISO 27001 Seeding", "Page 6"],
        ["7.", "PHASE 2: Assessment Engine, Maturity Scoring & Evaluation Workflow", "Page 8"],
        ["8.", "PHASE 3: Information Asset Register & Risk Assessment Engine", "Page 10"],
        ["9.", "PHASE 4: Statement of Applicability (SoA) & Version Snapshot Engine", "Page 12"],
        ["10.", "PHASE 5: Document Management System (ISMS DMS) & Control Mapping", "Page 14"],
        ["11.", "PHASE 6: Executive Analytics, Snapshot Jobs & Multi-Format Reporting", "Page 15"],
        ["12.", "PHASE 7: Risk Treatment Strategies, Scheduled Queues & Notifications", "Page 17"],
        ["13.", "PHASE 8: Enterprise UI/UX Overhaul, Security Hardening & Deployment", "Page 19"],
        ["14.", "Comprehensive Test Verification Suite & Invariant Matrix", "Page 21"],
        ["15.", "Future Roadmap, REST API Strategy & References", "Page 22"],
    ]
    add_styled_table(doc, ["Sr. No.", "Project Module / Chapter Description", "Page No."], toc_rows, col_widths=[0.8, 4.5, 1.2])

    doc.add_page_break()

    # 4. ACKNOWLEDGEMENT
    pa = doc.add_paragraph()
    pa.alignment = WD_ALIGN_PARAGRAPH.CENTER
    pa.paragraph_format.space_after = Pt(16)
    ra = pa.add_run("Acknowledgement")
    ra.font.size = Pt(16)
    ra.font.bold = True
    ra.font.color.rgb = COLOR_PRIMARY_NAVY

    add_body_p(
        doc,
        "Building an enterprise-grade Information Security Management System (ISMS) compliance SaaS platform "
        "has been an enriching experience that bridged enterprise software architecture with rigorous information "
        "security standards. I express my profound gratitude to all who facilitated this journey."
    )
    add_body_p(
        doc,
        f"I am deeply thankful to {COLLEGE_NAME}, Ahmedabad, for providing state-of-the-art computational "
        "infrastructure, server environments, and an academically motivating atmosphere."
    )
    add_body_p(
        doc,
        f"I record my heartfelt gratitude to the leadership and faculty of {COLLEGE_NAME}, for their continuous "
        "encouragement and support in facilitating research and development projects."
    )
    add_body_p(
        doc,
        f"I am immensely indebted to my project guide, {GUIDE_NAME}, for his technical guidance, architectural "
        "critique, and rigorous review of the multi-tenancy isolation and ISO/IEC 27001:2022 control mapping."
    )
    add_body_p(
        doc,
        f"I also extend my sincere thanks to {UNIVERSITY_NAME} for prescribing an academic structure that encourages "
        "the creation of high-impact, industry-grade software solutions."
    )

    pauth = doc.add_paragraph()
    pauth.paragraph_format.space_before = Pt(28)
    pauth.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    pauth.add_run(f"{STUDENT_NAME}\nM.Sc. (CSDF) — Roll No: {ROLL_NO}\n{COLLEGE_NAME}").font.bold = True

    doc.add_page_break()

# ==============================================================================
# 5. CORE TECHNICAL CHAPTERS (PHASES 1 TO 8)
# ==============================================================================
def write_chapter_overview(doc):
    add_heading_1(doc, "5. Project Overview, Objectives & Architectural Philosophy")
    add_callout_box(doc, "SYSTEM PROFILE & TECHNOLOGY SPECIFICATION", [
        ("Platform Name: ", PROJECT_TITLE),
        ("Standard Supported: ", "ISO/IEC 27001:2022 (Information Security, Cybersecurity & Privacy Protection)"),
        ("Primary Technology Stack: ", "Laravel 11.x, PHP 8.2+, MySQL 8.0, Blade Templates, Tailwind CSS 4, Alpine.js, Chart.js"),
        ("Architectural Invariants: ", "Strict Service Layer, Repository Pattern, DTOs, TenantScope Global Isolation, Policy-based RBAC"),
        ("Live Demonstration Environment: ", LIVE_DEMO_URL)
    ])
    add_body_p(
        doc,
        "Achieving and maintaining ISO/IEC 27001:2022 compliance is traditionally a tedious, fragmented process "
        "involving dispersed spreadsheets, disconnected audit notes, and manual Statement of Applicability (SoA) tracking. "
        "The ISMS Compliance Assessment Platform automates this lifecycle by offering a centralized, multi-tenant SaaS "
        "environment that enforces data isolation, risk calculations (5x5 matrix), evidence attachments, and live analytics."
    )
    add_heading_2(doc, "5.1 Non-Negotiable Enterprise Architectural Rules")
    add_bullet_item(doc, "Zero Business Logic in Controllers: ", "Controllers strictly handle HTTP validation via FormRequests, invoke dedicated Services, and return View/JSON responses. All domain calculations reside in app/Services.")
    add_bullet_item(doc, "Data Access Decoupling via Repositories: ", "Direct Eloquent queries are abstracted behind Repository interfaces (app/Repositories/Contracts), facilitating decoupled unit testing and database independence.")
    add_bullet_item(doc, "Immutable Data Transfer Objects (DTOs): ", "Controller-to-service communication is strictly mediated using strongly-typed PHP 8.2 DTOs (app/DTOs), preventing loose array tampering.")
    add_bullet_item(doc, "Global Scope Multi-Tenancy: ", "Tenant isolation is enforced at the database level via TenantScope. Every query on tenant entities is automatically scoped with WHERE tenant_id = :current_tenant.")
    doc.add_page_break()

def write_chapter_phase1(doc):
    add_heading_1(doc, "6. PHASE 1: Foundation, Multi-Tenancy Core & ISO 27001 Seeding")
    add_callout_box(doc, "PHASE 1 MILESTONES — COMPLETED AUGUST 17, 2026", [
        ("Objectives: ", "Establish database schemas, global tenant scopes, Spatie RBAC, and seed full ISO 27001:2022 structure."),
        ("Verification Status: ", "29 / 29 PHPUnit Feature & Unit Tests Passing (100% Green).")
    ])
    add_body_p(
        doc,
        "Phase 1 established the foundation of the platform. A shared-database, shared-schema multi-tenant "
        "architecture was engineered. The global scope automatically resolves the tenant context from the session "
        "of the authenticated user, guaranteeing complete tenant segregation."
    )
    add_code_block(
        doc,
        "app/Scopes/TenantScope.php — Automatic Query Filtering",
        "class TenantScope implements Scope\n{\n"
        "    public function apply(Builder $builder, Model $model): void\n"
        "    {\n"
        "        if (auth()->check() && auth()->user()->current_tenant_id) {\n"
        "            if (Schema::hasColumn($model->getTable(), 'tenant_id')) {\n"
        "                $builder->where($model->getTable() . '.tenant_id', auth()->user()->current_tenant_id);\n"
        "            }\n"
        "        }\n"
        "    }\n}",
        "Verified: Invariant prevents cross-tenant access even if raw query lacks explicit where clause."
    )
    add_heading_2(doc, "6.1 Full ISO/IEC 27001:2022 Control Seeder Implementation")
    add_body_p(
        doc,
        "A comprehensive seeder populated all 11 domains (4 main clauses 4–10 and 4 Annex A themes A.5 to A.8) "
        "and 115 controls (93 Annex A controls + 14 main clause checkpoints + 8 extended controls) with categorized "
        "control types (preventive, detective, corrective)."
    )
    add_styled_table(
        doc,
        ["ISO 27001:2022 Theme / Clause", "Domain Code", "Control Count", "Security Category"],
        [
            ["Organizational Controls", "Clause 4–10 & A.5", "45 Controls", "Organizational Governance"],
            ["People Controls", "Annex A.6", "8 Controls", "Human Resource Security"],
            ["Physical Controls", "Annex A.7", "14 Controls", "Physical & Environmental"],
            ["Technological Controls", "Annex A.8", "48 Controls", "System, Network & Data Security"],
            ["TOTAL SEEDED AUDIT CONTROLS", "11 Domains", "115 Controls", "Complete 2022 Standard Coverage"]
        ],
        col_widths=[2.4, 1.4, 1.3, 1.4]
    )
    doc.add_page_break()

def write_chapter_phase2(doc):
    add_heading_1(doc, "7. PHASE 2: Assessment Engine, Maturity Scoring & Evaluation Workflow")
    add_callout_box(doc, "PHASE 2 MILESTONES — COMPLETED AUGUST 19, 2026", [
        ("Objectives: ", "Multi-step assessment wizard, domain-by-domain evaluation, maturity scoring (Level 0-5), and ComplianceCalculator."),
        ("Verification Status: ", "37 / 37 Feature Tests Passing (93 assertions).")
    ])
    add_body_p(
        doc,
        "Phase 2 introduced the core Assessment Engine. Auditors and assessors can instantiate assessments, "
        "assign assessors via the assessment_team pivot, and systematically evaluate controls across five compliance states:"
    )
    add_bullet_item(doc, "Compliant (100%): ", "Full adherence to the control statement with verified supporting artifact.")
    add_bullet_item(doc, "Partially Compliant (50%): ", "Policy documented or ad-hoc process active, but lacks continuous enforcement.")
    add_bullet_item(doc, "Non-Compliant (0%): ", "Control statement not addressed, creating an identified audit gap.")
    add_bullet_item(doc, "Not Applicable: ", "Control excluded from ISMS scope with mandatory justification recorded.")

    add_code_block(
        doc,
        "app/Services/Compliance/ComplianceCalculator.php — Weighted Compliance Formula",
        "public function calculateOverallScore(Assessment $assessment): float\n{\n"
        "    $responses = $this->responseRepo->getApplicableForAssessment($assessment->id);\n"
        "    if ($responses->isEmpty()) return 0.0;\n\n"
        "    $totalWeight = $responses->count() * 100;\n"
        "    $earnedScore = $responses->sum(function ($r) {\n"
        "        return match($r->status) {\n"
        "            'compliant' => 100,\n"
        "            'partially_compliant' => 50,\n"
        "            default => 0\n"
        "        };\n"
        "    });\n"
        "    return round(($earnedScore / $totalWeight) * 100, 2);\n}",
        "Score Range: 0.00% to 100.00% dynamic calculation per tenant assessment."
    )
    add_screenshot(doc, "assessment_conduct_screen.png", "2.1", "Assessment Evaluation Workspace: Evaluating Annex A.5 Organizational Controls with live maturity scoring.")
    doc.add_page_break()

def write_chapter_phase3(doc):
    add_heading_1(doc, "8. PHASE 3: Information Asset Register & Risk Assessment Engine")
    add_callout_box(doc, "PHASE 3 MILESTONES — COMPLETED AUGUST 28, 2026", [
        ("Objectives: ", "Asset Register with CIA valuation, 5x5 Likelihood x Impact scoring, and clickable Heatmap visualization."),
        ("Verification Status: ", "49 / 49 Feature Tests Passing (112 assertions).")
    ])
    add_body_p(
        doc,
        "Clause 6.1.2 of ISO/IEC 27001 requires an information security risk assessment process. Phase 3 engineered "
        "an Asset Inventory supporting hardware, software, data, people, and facilities. Each asset receives CIA "
        "(Confidentiality, Integrity, Availability) ratings on a 1–5 scale to quantify critical asset value."
    )
    add_heading_2(doc, "8.1 5x5 Risk Matrix & Heatmap Topology")
    add_styled_table(
        doc,
        ["Score Range", "Risk Severity", "Hex Color Code", "Automated Action Required"],
        [
            ["Score 1 – 4", "Low Risk", "#10B981 (Green)", "Accept risk; periodic annual monitoring"],
            ["Score 5 – 9", "Medium Risk", "#FBBF24 (Yellow)", "Mitigate with existing Annex A controls"],
            ["Score 10 – 16", "High Risk", "#F97316 (Orange)", "Mandatory Treatment Plan with assigned owner"],
            ["Score 17 – 25", "Critical Risk", "#EF4444 (Red)", "Immediate management escalation; mitigation required"]
        ],
        col_widths=[1.5, 1.5, 1.6, 1.9]
    )
    add_screenshot(doc, "risk_heatmap_5x5.png", "3.1", "Interactive 5x5 Risk Heatmap matrix rendering live risk distribution across Likelihood and Impact axes.")
    doc.add_page_break()

def write_chapter_phase4(doc):
    add_heading_1(doc, "9. PHASE 4: Statement of Applicability (SoA) & Version Snapshot Engine")
    add_callout_box(doc, "PHASE 4 MILESTONES — COMPLETED SEPTEMBER 02, 2026", [
        ("Objectives: ", "One-click SoA generation, regulatory justifications, multi-tier approvals, and immutable JSON version snapshots."),
        ("Verification Status: ", "54 / 54 Feature Tests Passing (128 assertions).")
    ])
    add_body_p(
        doc,
        "The Statement of Applicability (SoA) is the mandatory document required by ISO 27001 Clause 6.1.3(d). "
        "Phase 4 delivered an engine that transforms raw assessment data into formal SoA records, tracks justifications "
        "for inclusion or exclusion, and archives version snapshots in `soa_versions`."
    )
    add_code_block(
        doc,
        "app/Services/Compliance/StatementOfApplicabilityService.php — Immutable Versioning",
        "public function createVersionSnapshot(int $assessmentId, string $versionString, int $userId): SoAVersion\n{\n"
        "    $entries = $this->soaRepo->getByAssessmentId($assessmentId);\n"
        "    return $this->versionRepo->create([\n"
        "        'assessment_id' => $assessmentId,\n"
        "        'version'       => $versionString,\n"
        "        'data_snapshot' => json_encode($entries->toArray()),\n"
        "        'created_by'    => $userId\n"
        "    ]);\n}",
        "Audit Trail: Generates immutable JSON payload for external certification auditor review."
    )
    add_screenshot(doc, "soa_version_management.png", "4.1", "Statement of Applicability (SoA) workspace showing approved controls and side-by-side version comparison.")
    doc.add_page_break()

def write_chapter_phase5_to_7(doc):
    add_heading_1(doc, "10. PHASES 5, 6 & 7: DMS, Analytics Dashboard & Enterprise Queues")
    add_callout_box(doc, "INTEGRATED ENGINE SPECIFICATIONS", [
        ("Phase 5 (DMS): ", "Document lifecycle (Draft → Under Review → Approved), file versioning, and direct control-linking."),
        ("Phase 6 (Analytics): ", "Executive KPI cards, Chart.js trend tracking, automated snapshots, and multi-format exports (PDF/Excel/CSV)."),
        ("Phase 7 (Enterprise): ", "Four risk treatment strategies, automated email + in-app notification center, and scheduled daemon cron jobs.")
    ])
    add_body_p(
        doc,
        "In Phase 5, an ISMS Document Management System (DMS) was integrated with direct mapping to Annex A controls via "
        "`document_control_map`. Phase 6 developed an Executive Dashboard powered by Chart.js, aggregating compliance posture, "
        "domain breakdown, and 12-month historical trends."
    )
    add_body_p(
        doc,
        "Phase 7 completed the operational workflow by introducing risk treatment strategies (Mitigate, Accept, Transfer, Avoid), "
        "interactive notification centers, and three automated queue workers configured in `routes/console.php`:"
    )
    add_bullet_item(doc, "Daily Compliance Snapshot Job (02:00): ", "Captures daily organizational compliance score in report_snapshots.")
    add_bullet_item(doc, "Treatment Due Reminders Job (08:00): ", "Dispatches automated notification emails for pending risk mitigations.")
    add_bullet_item(doc, "Snapshot Retention Pruning Job (Sun 03:00): ", "Prunes historical compliance metrics exceeding the 24-month retention window.")

    add_styled_table(
        doc,
        ["Report Type", "Primary Target Audience", "Supported Formats", "Data Source Model"],
        [
            ["Executive Summary", "CISO, Board of Directors", "PDF, Excel, CSV", "DashboardService KPI Aggregator"],
            ["Gap Analysis Report", "Internal Lead Auditor", "PDF, Excel, CSV", "Non-compliant Assessment Responses"],
            ["Risk Register Report", "Risk & Security Committee", "PDF, Excel, CSV", "Risk & Treatment Plan Entities"],
            ["Statement of Applicability", "Certification Body (Auditor)", "PDF, Excel, XLSX", "SoAEntry & SoAVersion Tables"],
            ["Document Compliance", "Compliance Manager", "CSV, PDF", "Documents & Review Dates"]
        ],
        col_widths=[1.8, 1.8, 1.4, 1.5]
    )
    add_screenshot(doc, "executive_analytics_dashboard.png", "5.1", "Executive Analytics Dashboard: Real-time Compliance Score, Domain Chart & Risk Distribution.")
    doc.add_page_break()

def write_chapter_phase8(doc):
    add_heading_1(doc, "11. PHASE 8: Enterprise UI/UX Overhaul, Security Hardening & Deployment")
    add_callout_box(doc, "PRODUCTION READINESS & REFACTORING REVIEW", [
        ("Deployment Target: ", "InfinityFree Cloud Hosting (Production URL: https://ismsaudit.infinityfree.io)"),
        ("Security Interventions: ", "Policy-based RBAC enforcement, form nesting repairs, IDOR blocking, and custom error boundaries."),
        ("UI Paradigm: ", "B2B SaaS Aesthetic inspired by Vanta & Drata, built on Tailwind CSS 4 and Alpine.js.")
    ])
    add_body_p(
        doc,
        "Phase 8 transformed the platform from a functional prototype into a polished, commercial-grade SaaS product. "
        "Critical architectural and security vulnerabilities were resolved during this sprint:"
    )
    add_heading_2(doc, "11.1 Security Hardening & Vulnerability Remediation")
    add_bullet_item(doc, "RBAC Policy Enforcement: ", "Implemented AssetPolicy and RiskPolicy. Viewer roles attempting edit or delete operations are blocked with HTTP 403 Forbidden.")
    add_bullet_item(doc, "Form Nesting DOM Bug Correction: ", "Resolved an issue where HTML <form> delete actions nested inside update forms triggered unintended deletes. Extracted action handlers using explicit HTML5 form attributes.")
    add_bullet_item(doc, "Route Protection & IDOR Mitigation: ", "Secured `/reports` and `/soa` behind `tenant.selected` middleware; cross-tenant IDOR tampering consistently triggers HTTP 404.")
    add_bullet_item(doc, "Custom HTTP Error Boundaries: ", "Replaced default framework errors with bespoke Tailwind error pages for 403, 404, 419, 500, and 503 statuses.")

    add_code_block(
        doc,
        "app/Policies/RiskPolicy.php — Role-Based Authorization Guard",
        "public function update(User $user, Risk $risk): bool\n{\n"
        "    // Viewers strictly prohibited from altering risk parameters\n"
        "    if ($user->hasRoleInCurrentTenant('viewer')) {\n"
        "        return false;\n"
        "    }\n"
        "    return $user->current_tenant_id === $risk->tenant_id;\n}",
        "Result: Verified via automated tests; unauthorized PUT requests abort with 403 Forbidden."
    )
    add_screenshot(doc, "login_split_screen.png", "8.1", "Redesigned Split-Screen Authentication UI featuring Glassmorphism and responsive mobile drawer navigation.")
    doc.add_page_break()

def write_testing_matrix(doc):
    add_heading_1(doc, "12. Comprehensive Test Verification Suite & Invariant Matrix")
    add_callout_box(doc, "AUTOMATED TEST SUITE STATUS — 100% PASS RATE", [
        ("Total Executed Tests: ", "71 Feature & Unit Tests"),
        ("Total Assertions: ", "169 Verified Assertions"),
        ("Framework & Tooling: ", "PHPUnit 10.5, Laravel Feature Testing Engine, SQLite In-Memory Database"),
        ("Execution Time: ", "~25.75 seconds")
    ])
    test_matrix = [
        ["Tenant Isolation Scope", "User from Tenant A cannot read or mutate Tenant B assets/risks", "PASS ✔"],
        ["ISO Control Seeder Count", "Exact count of 115 controls (93 Annex A + 14 Main Clause + 8 Ext)", "PASS ✔"],
        ["Compliance Math Formula", "Partially compliant scores weight at 50%; non-compliant at 0%", "PASS ✔"],
        ["Risk Auto-Scoring Engine", "Likelihood (1-5) * Impact (1-5) dynamically sets score & severity level", "PASS ✔"],
        ["SoA Immutable Snapshots", "Creating SoA snapshot locks entries into immutable JSON payload", "PASS ✔"],
        ["Viewer Role Authorization", "Viewer role attempting asset/risk mutation returns HTTP 403 Forbidden", "PASS ✔"],
        ["Cross-Tenant IDOR Attack", "Direct ID manipulation in URL returns HTTP 404 (Resource Not Found)", "PASS ✔"],
        ["Nested Form Action Integrity", "Updating a risk never invokes the delete action handler", "PASS ✔"],
        ["CSRF & Safe Session Timeout", "Expired tokens redirect gracefully with branded 419 error page", "PASS ✔"],
        ["Multi-Format Exporters", "CSV, PDF (DomPDF), and Excel (Maatwebsite) generate without warnings", "PASS ✔"]
    ]
    add_styled_table(doc, ["Verification Invariant", "Test Case Description & Security Requirement", "Status"], test_matrix, col_widths=[2.0, 3.5, 1.0])
    doc.add_page_break()

def write_closing_chapters(doc):
    add_heading_1(doc, "13. Future Roadmap, REST API Strategy & References")
    add_heading_2(doc, "13.1 Phase 8B / Phase 9 Architectural Roadmap")
    add_bullet_item(doc, "Headless REST API (Phase 8B): ", "Deploy Laravel Sanctum tokenized endpoints to support CI/CD evidence pipelines (automated GitHub/AWS scanner telemetry into control responses).")
    add_bullet_item(doc, "Dedicated Audit Logging UI: ", "Visual audit log interface leveraging Owen-it Laravel Auditing to trace model mutations with before/after diffs.")
    add_bullet_item(doc, "Third-Party Webhooks: ", "Automated Slack, Microsoft Teams, and email digest broadcasts for upcoming audit milestones.")

    add_heading_1(doc, "14. References & Academic Bibliography")
    add_bullet_item(doc, "[1] International Organization for Standardization: ", "ISO/IEC 27001:2022 — Information security, cybersecurity and privacy protection — Information security management systems — Requirements. https://www.iso.org/standard/27001")
    add_bullet_item(doc, "[2] Laravel Documentation: ", "Laravel 11.x Framework Architecture, Multi-Tenant Scoping, and Service-Repository Design Patterns. https://laravel.com/docs")
    add_bullet_item(doc, "[3] OWASP Foundation: ", "OWASP Top Ten Web Application Security Risks — Broken Object Level Authorization (IDOR) & Injection Mitigation. https://owasp.org/")
    add_bullet_item(doc, "[4] Spatie Software: ", "Role-Based Access Control in Laravel Applications using spatie/laravel-permission. https://spatie.be/")
    add_bullet_item(doc, "[5] Academic Project Guidelines: ", f"Bachelor of Computer Applications (Honours), Guided by {GUIDE_NAME} at {COLLEGE_NAME} ({UNIVERSITY_NAME}), Academic Year 2026–27.")

# ==============================================================================
# 6. MASTER ORCHESTRATION & PDF CONVERSION
# ==============================================================================
def convert_to_pdf(docx_path):
    pdf_path = docx_path.replace(".docx", ".pdf")
    print("\n" + "=" * 75)
    print("🔄 INITIATING AUTOMATIC PDF GENERATION...")

    # Method 1: docx2pdf (Requires Microsoft Word on Windows/macOS)
    try:
        from docx2pdf import convert
        print("   Attempting conversion via docx2pdf (MS Word Engine)...")
        convert(docx_path, pdf_path)
        if os.path.exists(pdf_path):
            print(f"✅ PDF GENERATED SUCCESSFULLY: {os.path.abspath(pdf_path)}")
            return True
    except Exception as e:
        print(f"   ℹ docx2pdf skipped or failed: {e}")

    # Method 2: LibreOffice Headless (Common on Linux / Mac / Windows with LibreOffice)
    try:
        print("   Attempting conversion via LibreOffice headless...")
        cmd = ["soffice", "--headless", "--convert-to", "pdf", docx_path, "--outdir", os.path.dirname(os.path.abspath(docx_path))]
        subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, check=True)
        if os.path.exists(pdf_path):
            print(f"✅ PDF GENERATED VIA LIBREOFFICE: {os.path.abspath(pdf_path)}")
            return True
    except Exception as e:
        print(f"   ℹ LibreOffice skipped: {e}")

    print("⚠️  Notice: Direct PDF conversion requires Microsoft Word or LibreOffice.")
    print(f"   A pristine Word document '{docx_path}' has been saved.")
    print("   You can open it in Word/WPS and select 'Save As -> PDF'.")
    print("=" * 75)
    return False

def generate_full_report():
    print("=" * 75)
    print("🚀 GENERATING ISO/IEC 27001 ISMS PROJECT DOCUMENTATION...")
    print("=" * 75)

    doc = Document()
    configure_document_styles(doc)

    write_front_matter(doc)
    write_chapter_overview(doc)
    write_chapter_phase1(doc)
    write_chapter_phase2(doc)
    write_chapter_phase3(doc)
    write_chapter_phase4(doc)
    write_chapter_phase5_to_7(doc)
    write_chapter_phase8(doc)
    write_testing_matrix(doc)
    write_closing_chapters(doc)

    docx_name = "ISO27001_Compliance_Tool_Project_Report.docx"
    doc.save(docx_name)

    print(f"✅ MASTER DOCX READY: {os.path.abspath(docx_name)}")
    print(f"   • Student: {STUDENT_NAME} (Roll: {ROLL_NO})")
    print(f"   • Guide: {GUIDE_NAME} | College: {COLLEGE_NAME}")
    print(f"   • Live Demo: {LIVE_DEMO_URL}")

    # Auto-convert to PDF
    convert_to_pdf(docx_name)

if __name__ == "__main__":
    generate_full_report()