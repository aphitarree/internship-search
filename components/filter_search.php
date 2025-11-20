<?php
$faculty = $_GET['faculty'] ?? '';
$program = $_GET['program'] ?? '';
$major = $_GET['major'] ?? '';
$province = $_GET['province'] ?? '';
$academicYear = $_GET['academic-year'] ?? '';

require_once __DIR__ . '/../config/db_config.php';

$sql = "
    SELECT DISTINCT year
    FROM internship_stats;
";


$stmt = $conn->prepare($sql);
$stmt->execute();
$yearsArray = $stmt->fetchAll(PDO::FETCH_COLUMN);

// ดึงข้อมูลคณะ หลักสูตรสาขามาแทนข้อมูลดิบเก่าของที่เขียนไว้ในตัวแปร JavaScript
$facultyMap = [];

try {
    $sql = "
        SELECT faculty, program, major
        FROM faculty_program_major
        ORDER BY faculty, major, program
    ";
    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $facultyName = $row['faculty'];
        $majorName   = $row['major'];
        $programName = $row['program'];

        if (!isset($facultyMap[$facultyName])) {
            $facultyMap[$facultyName] = [];
        }

        $facultyMap[$facultyName][$majorName] = $programName;
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage();
}
?>

<section class="mx-auto max-w-[1625px] px-4 mt-10">
    <form id="filter-form" action="index.php" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="faculty" class="block mb-2 font-medium">คณะ / โรงเรียน</label>
                <select id="faculty" name="faculty" class="w-full mb-4 border rounded-md px-3 py-2">
                    <option value="">-เลือกคณะ / โรงเรียน-</option>
                </select>
            </div>

            <div>
                <label for="program" class="block mb-2 font-medium">หลักสูตร</label>
                <select id="program" name="program" class="w-full border rounded-md px-3 py-2">
                    <option value="">-เลือกหลักสูตร-</option>
                </select>
            </div>

            <div>
                <label for="major" class="block mb-2 font-medium">สาขาวิชา</label>
                <select id="major" name="major" class="w-full border rounded-md px-3 py-2">
                    <option value="">-เลือกสาขาวิชา-</option>
                </select>
            </div>


            <div>
                <label for="province" class="block mb-2 font-medium">จังหวัด</label>
                <select id="province" name="province" class="w-full border rounded-md px-3 py-2">
                    <option>-เลือกจังหวัด-</option>
                </select>
            </div>

            <div>
                <label for="academic-year" class="block mb-2 font-medium">ปีการศึกษา</label>
                <select id="academic-year" name="academic-year" class="w-full border rounded-md px-3 py-2">
                    <option>-เลือก พ.ศ.-</option>
                </select>
            </div>
        </div>

        <div class="mt-4 mb-3 flex items-center justify-center gap-3">
            <button class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-sky-500 hover:bg-sky-600 text-white font-bold" type="submit">
                ค้นหา
            </button>
            <!-- clear filter button -->
            <button
                id="clear-search-query"
                class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-gray-200 hover:bg-gray-300"
                type="button">
                ล้างการค้นหา
            </button>
        </div>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pass selected values from PHP to JavaScript
        const selectedFaculty = "<?php echo $faculty; ?>";
        const selectedMajor = "<?php echo $major; ?>";
        const selectedProgram = "<?php echo $program; ?>";
        const selectedProvince = "<?php echo $province; ?>";
        const selectedAcademicYear = "<?php echo $academicYear; ?>";

        // const facultyMajorsPrograms = {
        //     "ครุศาสตร์": {
        //         "การศึกษาปฐมวัย": "ศึกษาศาสตรบัณฑิต",
        //         "การประถมศึกษา": "ศึกษาศาสตรบัณฑิต",
        //     },
        //     "วิทยาศาสตร์และเทคโนโลยี": {
        //         "เทคโนโลยีสารสนเทศ": "วิทยาศาสตรบัณฑิต",
        //         "สิ่งแวดล้อมเมืองและอุตสาหกรรม": "วิทยาศาสตรบัณฑิต",
        //         "อุตสาหกรรมชีวภาพเพื่อธุรกิจ": "วิทยาศาสตรบัณฑิต",
        //         "วิทยาศาสตร์เครื่องสำอาง": "วิทยาศาสตรบัณฑิต",
        //         "อาชีวอนามัยและความปลอดภัย": "วิทยาศาสตรบัณฑิต",
        //         "อนามัยสิ่งแวดล้อมและสาธารณภัย": "วิทยาศาสตรบัณฑิต",
        //         "เทคโนโลยีเคมี": "วิทยาศาสตรบัณฑิต",
        //         "วิทยาการคอมพิวเตอร์": "วิทยาศาสตรบัณฑิต",
        //         "คณิตศาสตร์": "ศึกษาศาสตรบัณฑิต",
        //         "ฟิสิกส์": "ศึกษาศาสตรบัณฑิต",
        //         "ความมั่นคงปลอดภัยไซเบอร์": "วิทยาศาสตรบัณฑิต",
        //         "วิทยาศาสตร์การแพทย์": "วิทยาศาสตรบัณฑิต",
        //         "วิทยาศาสตร์ทั่วไป": "วิทยาศาสตรบัณฑิต",
        //     },
        //     "วิทยาการจัดการ": {
        //         "การบัญชี": "บัญชีบัณฑิต",
        //         "การเงิน": "บริหารธุรกิจบัณฑิต",
        //         "การตลาด": "บริหารธุรกิจบัณฑิต",
        //         "บริหารธุรกิจ": "บริหารธุรกิจบัณฑิต",
        //         "การจัดการ": "การจัดการบัณฑิต",
        //         "การบริการลูกค้า": "บริหารธุรกิจบัณฑิต",
        //         "การจัดการธุรกิจค้าปลีก": "บริหารธุรกิจบัณฑิต",
        //         "การจัดการทรัพยากรมนุษย์": "บริหารธุรกิจบัณฑิต",
        //         "คอมพิวเตอร์ธุรกิจ": "บริหารธุรกิจบัณฑิต",
        //         "ธุรกิจระหว่างประเทศ": "บริหารธุรกิจบัณฑิต",
        //         "ธุรกิจระหว่างประเทศ ธุรกิจจีน-อาเซียน": "บริหารธุรกิจบัณฑิต",
        //         "เลขานุการทางการแพทย์": "บริหารธุรกิจบัณฑิต",
        //         "ธุรกิจสร้างสรรค์และเทคโนโลยีดิจิทัล": "บริหารธุรกิจบัณฑิต",
        //         "การจัดการบัณฑิต (การจัดการธุรกิจความงาม, การจัดการธุรกิจอาหาร)": "ศิลปศาสตรบัณฑิต",
        //         "การตลาดและการสร้างแบรนด์บุคคล": "ศิลปศาสตรบัณฑิต",
        //         "นิเทศศาสตร์นวัตกรรมและการเป็นผู้ประกอบการธุรกิจ": "ศิลปศาสตรบัณฑิต",
        //         "การสื่อสารดิจิทัลและการสร้างสรรค์คอนเทนต์": "ศิลปศาสตรบัณฑิต",
        //         "นิเทศศาสตร์": "นิเทศศาสตรบัณฑิต",
        //     },
        //     "มนุษยศาสตร์และสังคมศาสตร์": {
        //         "ภาษาอังกฤษธุรกิจ": "ศิลปศาสตรบัณฑิต",
        //         "จิตวิทยาอุตสาหกรรมและองค์การ": "ศิลปศาสตรบัณฑิต",
        //         "ภาษาและวัฒนธรรมไทย": "ศิลปศาสตรบัณฑิต",
        //         "ภาษาอังกฤษเพื่อการสื่อสาร": "ศิลปศาสตรบัณฑิต",
        //         "ภาษาและการสื่อสาร": "ศิลปศาสตรบัณฑิต",
        //         "ภาษาจีนเพื่อการสื่อสาร": "ศิลปศาสตรบัณฑิต",
        //         "ภาษาจีนเพื่อการบริการ": "ศิลปศาสตรบัณฑิต",
        //         "ภาษาอังกฤษและภาษาเกาหลีเพื่อการสื่อสารข้ามวัฒนธรรม": "ศิลปศาสตรบัณฑิต",
        //         "ศิลปศึกษา": "ศึกษาศาสตรบัณฑิต",
        //         "การออกแบบศิลปะสร้างสรรค์ด้วยปัญญาประดิษฐ์": "ศึกษาศาสตรบัณฑิต",
        //         "บรรณารักษศาสตร์และสารสนเทศศาสตร์": "ศิลปศาสตรบัณฑิต",
        //     },
        //     "พยาบาลศาสตร์": {
        //         "พยาบาลศาสตร์": "พยาบาลศาสตรบัณฑิต"
        //     },
        //     "การท่องเที่ยวและการบริการ": {
        //         "การท่องเที่ยว": "ศิลปศาสตรบัณฑิต",
        //         "ธุรกิจการโรงแรม": "ศิลปศาสตรบัณฑิต",
        //         "ธุรกิจการบิน": "ศิลปศาสตรบัณฑิต",
        //         "ออกแบบนิทรรศการและการแสดง": "ศิลปศาสตรบัณฑิต",
        //         "การจัดการงานบริการ (นานาชาติ)": "ศิลปศาสตรบัณฑิต",
        //         "การจัดการไมซ์และอีเวนต์": "ศิลปศาสตรบัณฑิต",
        //         "การท่องเที่ยวและมัคคุเทศก์": "ศิลปศาสตรบัณฑิต",
        //         "ผู้ประกอบการธุรกิจบริการและการท่องเที่ยว": "ศิลปศาสตรบัณฑิต",
        //     },
        //     "การเรือน": {
        //         "อุตสาหกรรมการประกอบอาหาร": "วิทยาศาสตรบัณฑิต",
        //         "โภชนาการและการประกอบอาหาร": "วิทยาศาสตรบัณฑิต",
        //         "โภชนาการและการประกอบอาหารเพื่อการสร้างเสริมสมรรถภาพและการชะลอวัย": "วิทยาศาสตรบัณฑิต",
        //         "เทคโนโลยีการประกอบอาหารและการบริการ": "วิทยาศาสตรบัณฑิต",
        //         "คหกรรมศาสตร์": "ศิลปศาสตรบัณฑิต",
        //         "นวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร": "ศิลปศาสตรบัณฑิต",
        //     },
        //     "กฎหมายและการเมือง": {
        //         "นิติศาสตร์": "นิติศาสตรบัณฑิต",
        //         "รัฐประศาสนศาสตร์": "รัฐประศาสนศาสตรบัณฑิต",
        //         "รัฐศาสตร์": "รัฐศาสตรบัณฑิต",
        //     },

        // };

        const facultyMajorsPrograms = <?= json_encode($facultyMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        const sortChoice = (a, b) => {
            if (a.value === '' && b.value !== '') return -1;
            if (a.value !== '' && b.value === '') return 1;
            return a.label.localeCompare(b.label, 'th');
        }

        const sortChoiceDesc = (a, b) => {
            if (a.value === '' && b.value !== '') return 1;
            if (a.value !== '' && b.value === '') return -1;
            return b.label.localeCompare(a.label, 'th');
        }

        const facultySelect = document.getElementById("faculty");
        const majorSelect = document.getElementById("major");
        const programSelect = document.getElementById("program");

        const facultyChoices = new Choices(facultySelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาคณะ / โรงเรียน...",
            // shouldSort: false,
            sorter: sortChoice,
        });
        const majorChoices = new Choices(majorSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาสาขาวิชา...",
            // shouldSort: false,
            sorter: sortChoice,
        });
        const programChoices = new Choices(programSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาหลักสูตร...",
            // shouldSort: false,
            sorter: sortChoice,
        });

        const allFaculties = Object.keys(facultyMajorsPrograms);
        const allMajors = Object.values(facultyMajorsPrograms)
            .flatMap(majorsObj => Object.keys(majorsObj));
        const allPrograms = [...new Set(
            Object.values(facultyMajorsPrograms)
            .flatMap(majorsObj => Object.values(majorsObj))
        )];

        // Create function for populate the dropdown
        const populateFaculties = (list) => {
            facultyChoices.clearStore();
            facultyChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกคณะ / โรงเรียน-",
                    selected: true,
                    disabled: false
                }]
                .concat(list.map(faculty => ({
                    value: faculty,
                    label: faculty
                }))),
                "value", "label", true
            );
        };
        const populateMajors = (list) => {
            majorChoices.clearStore();
            majorChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกสาขาวิชา-",
                    selected: true,
                    disabled: false
                }]
                .concat(list.map(major => ({
                    value: major,
                    label: major
                }))),
                "value", "label", true
            );
        };
        const populatePrograms = (list) => {
            programChoices.clearStore();
            programChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกหลักสูตร-",
                    selected: true,
                    disabled: false
                }]
                .concat(list.map(program => ({
                    value: program,
                    label: program
                }))),
                "value", "label", true
            );
        };

        // Populate value to the dropdown
        populateFaculties(allFaculties);
        populateMajors(allMajors);
        populatePrograms(allPrograms);

        // Set initial values from URL parameters
        if (selectedFaculty) {
            facultyChoices.setChoiceByValue(selectedFaculty);
        }
        if (selectedMajor) {
            majorChoices.setChoiceByValue(selectedMajor);
        }
        if (selectedProgram) {
            programChoices.setChoiceByValue(selectedProgram);
        }

        // If select faculty, filter majors and programs
        facultySelect.addEventListener("change", () => {
            const faculty = facultySelect.value;
            const selectedProgram = programSelect.value || "";

            const getProgramsOfFaculty = (name) => {
                if (name && facultyMajorsPrograms[name]) {
                    return [...new Set(Object.values(facultyMajorsPrograms[name]))];
                } else {
                    return allPrograms;
                }
            };

            const getMajorsOfFaculty = (name) => {
                if (name && facultyMajorsPrograms[name]) {
                    return Object.keys(facultyMajorsPrograms[name]);
                } else {
                    return allMajors;
                }
            }

            const getFacultiesByProgram = (prog) => {
                if (!prog) return allFaculties;
                const set = new Set();
                for (const [fac, majorsObj] of Object.entries(facultyMajorsPrograms)) {
                    for (const p of Object.values(majorsObj)) {
                        if (p === prog) {
                            set.add(fac);
                            break;
                        }
                    }
                }
                return [...set];
            };

            if (faculty) {
                const programsOfFaculty = getProgramsOfFaculty(faculty);
                const programToKeep = selectedProgram && programsOfFaculty.includes(selectedProgram) ?
                    selectedProgram : "";

                let majorsList = [];
                if (programToKeep) {
                    majorsList = Object.entries(facultyMajorsPrograms[faculty])
                        .filter(([, prog]) => prog === programToKeep)
                        .map(([major]) => major);
                } else {
                    majorsList = getMajorsOfFaculty(faculty);
                }

                populateMajors(majorsList);
                populatePrograms(programsOfFaculty);
                programChoices.setChoiceByValue(programToKeep || "");
                return;
            }

            if (!faculty && selectedProgram) {
                const facultiesByProg = getFacultiesByProgram(selectedProgram);
                const majorsByProg = [];
                for (const [fac, majorsObj] of Object.entries(facultyMajorsPrograms)) {
                    for (const [major, prog] of Object.entries(majorsObj)) {
                        if (prog === selectedProgram) majorsByProg.push(major);
                    }
                }
                populateFaculties(facultiesByProg);
                populateMajors(majorsByProg);
                programChoices.setChoiceByValue(selectedProgram);
                facultyChoices.setChoiceByValue("");
                return;
            }

            // reset
            populateMajors(allMajors);
            populatePrograms(allPrograms);
            programChoices.setChoiceByValue("");
            facultyChoices.setChoiceByValue("");
        });

        // If select major, auto select faculty and program
        majorSelect.addEventListener("change", () => {
            const major = majorSelect.value;
            if (!major) return;

            let selectedFaculty = null;
            let selectedProgram = null;

            for (const [faculty, majorsObj] of Object.entries(facultyMajorsPrograms)) {
                if (major in majorsObj) {
                    selectedFaculty = faculty;
                    selectedProgram = majorsObj[major];
                    break;
                }
            }

            if (selectedFaculty && selectedProgram) {
                facultyChoices.setChoiceByValue(selectedFaculty);

                // ensure program dropdown is scoped to that faculty before set value
                const programsOfFaculty = [...new Set(Object.values(facultyMajorsPrograms[selectedFaculty]))];
                populatePrograms(programsOfFaculty);
                programChoices.setChoiceByValue(selectedProgram || "");
            }
        });

        // If select program, filter faculties and majors
        programSelect.addEventListener("change", () => {
            const prog = programSelect.value;

            if (!prog) {
                // reset all
                populateFaculties(allFaculties);
                populateMajors(allMajors);
                populatePrograms(allPrograms);
                facultyChoices.setChoiceByValue("");
                majorChoices.setChoiceByValue("");
                programChoices.setChoiceByValue("");
                return;
            }

            const majorsOfProgram = [];
            const facultiesOfProgramSet = new Set();
            for (const [faculty, majorsObj] of Object.entries(facultyMajorsPrograms)) {
                for (const [major, programName] of Object.entries(majorsObj)) {
                    if (programName === prog) {
                        majorsOfProgram.push(major);
                        facultiesOfProgramSet.add(faculty);
                    }
                }
            }
            const facultiesOfProgram = [...facultiesOfProgramSet];
            const selectedFaculty = facultySelect.value || "";

            if (selectedFaculty && facultiesOfProgramSet.has(selectedFaculty)) {
                const majorsInSelectedFaculty = Object.entries(facultyMajorsPrograms[selectedFaculty])
                    .filter(([, programName]) => programName === prog)
                    .map(([major]) => major);

                populateFaculties([selectedFaculty]);
                facultyChoices.setChoiceByValue(selectedFaculty);

                populateMajors(majorsInSelectedFaculty);
                majorChoices.setChoiceByValue("");

                const programsOfSelectedFaculty = [...new Set(Object.values(facultyMajorsPrograms[selectedFaculty]))];
                populatePrograms(programsOfSelectedFaculty);
                programChoices.setChoiceByValue(prog);
            } else {
                populateFaculties(facultiesOfProgram);
                populateMajors(majorsOfProgram);
                facultyChoices.setChoiceByValue("");
                majorChoices.setChoiceByValue("");

                // keep program obvious (only itself)
                populatePrograms([prog]);
                programChoices.setChoiceByValue(prog);
            }
        });

        const provinces = [
            "กรุงเทพมหานคร", "กระบี่", "กาญจนบุรี", "กาฬสินธุ์", "กำแพงเพชร",
            "ขอนแก่น", "จันทบุรี", "ฉะเชิงเทรา", "ชลบุรี", "ชัยนาท", "ชัยภูมิ",
            "ชุมพร", "เชียงราย", "เชียงใหม่", "ตรัง", "ตราด", "ตาก", "นครนายก",
            "นครปฐม", "นครพนม", "นครราชสีมา", "นครศรีธรรมราช", "นครสวรรค์",
            "นนทบุรี", "นราธิวาส", "น่าน", "บึงกาฬ", "บุรีรัมย์", "ปทุมธานี",
            "ประจวบคีรีขันธ์", "ปราจีนบุรี", "ปัตตานี", "พระนครศรีอยุธยา",
            "พะเยา", "พังงา", "พัทลุง", "พิจิตร", "พิษณุโลก", "เพชรบุรี",
            "เพชรบูรณ์", "แพร่", "ภูเก็ต", "มหาสารคาม", "มุกดาหาร", "แม่ฮ่องสอน",
            "ยโสธร", "ยะลา", "ร้อยเอ็ด", "ระนอง", "ระยอง", "ราชบุรี", "ลพบุรี",
            "ลำปาง", "ลำพูน", "เลย", "ศรีสะเกษ", "สกลนคร", "สงขลา", "สตูล",
            "สมุทรปราการ", "สมุทรสงคราม", "สมุทรสาคร", "สระแก้ว", "สระบุรี",
            "สิงห์บุรี", "สุโขทัย", "สุพรรณบุรี", "สุราษฎร์ธานี", "สุรินทร์",
            "หนองคาย", "หนองบัวลำภู", "อ่างทอง", "อำนาจเจริญ", "อุดรธานี",
            "อุตรดิตถ์", "อุทัยธานี", "อุบลราชธานี"
        ];

        const provinceSelect = document.getElementById("province");

        const provinceChoices = new Choices(provinceSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาจังหวัด...",
            // shouldSort: false,
            sorter: sortChoice,
        });

        const populateProvinces = (list) => {
            provinceChoices.clearStore();
            provinceChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกจังหวัด-",
                    selected: true,
                    disabled: false
                }]
                .concat(list.map(province => ({
                    value: province,
                    label: province,
                }))),
                "value", "label", true,
            );
        };

        populateProvinces(provinces);

        if (selectedProvince) {
            provinceChoices.setChoiceByValue(selectedProvince);
        }

        const academicYears = <?php echo json_encode($yearsArray); ?>;
        const academicYearsString = academicYears.map(String);

        const academicYearSelect = document.getElementById("academic-year");

        const academicYearChoices = new Choices(academicYearSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาปีการศึกษา...",
            sorter: sortChoiceDesc,
        });

        const populateAcademicYears = (list) => {
            academicYearChoices.clearStore();
            academicYearChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกปีการศึกษา-",
                    selected: true,
                    disabled: false
                }]
                .concat(list.map(year => ({
                    value: year,
                    label: year
                }))),
                "value", "label", true
            );
        };

        populateAcademicYears(academicYearsString);

        if (selectedAcademicYear) {
            academicYearChoices.setChoiceByValue(selectedAcademicYear);
        }

        // Clear search button
        const clearSearchButton = document.getElementById('clear-search-query');
        if (clearSearchButton) {
            clearSearchButton.addEventListener('click', (event) => {
                event.preventDefault();

                window.location.href = window.location.pathname;
            });
        }
    });
</script>