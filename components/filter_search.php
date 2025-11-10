<section class="mx-auto max-w-[1625px] px-4 mt-10">
    <form action="index.php" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="faculty" class="block mb-2 font-medium">คณะ</label>
                <select id="faculty" name="faculty" class="w-full mb-4 border rounded-md px-3 py-2">
                    <option value="">-เลือกคณะ-</option>
                </select>
            </div>

            <div>
                <label for="major" class="block mb-2 font-medium">สาขา</label>
                <select id="major" name="major" class="w-full border rounded-md px-3 py-2">
                    <option value="">-เลือกสาขา-</option>
                </select>
            </div>

            <div>
                <label for="program" class="block mb-2 font-medium">หลักสูตร</label>
                <select id="program" name="program" class="w-full border rounded-md px-3 py-2">
                    <option value="">-เลือกหลักสูตร-</option>
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
                    <option value="2568">2568</option>
                    <option value="2567">2567</option>
                    <option value="2566">2566</option>
                    <option value="2565">2565</option>
                    <option value="2564">2564</option>
                    <option value="2563">2563</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-center gap-3">
            <input type="text" placeholder="Search..." class="w-full max-w-[540px] h-11 rounded-md border border-gray-400 px-4" />
            <button class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-gray-200 hover:bg-gray-300" type="submit">
                ค้นหา
            </button>
            <!-- clear filter button -->
            <button class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-gray-200 hover:bg-gray-300" type="button" onclick="window.location.href = window.location.pathname;">
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

        const facultyMajorsPrograms = {
            "คณะครุศาสตร์": {
                "การศึกษาปฐมวัย": "หลักสูตรศึกษาศาสตรบัณฑิต",
                "การประถมศึกษา": "หลักสูตรศึกษาศาสตรบัณฑิต",
            },
            "คณะวิทยาศาสตร์และเทคโนโลยี": {
                "เทคโนโลยีสารสนเทศ": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "สิ่งแวดล้อมเมืองและอุตสาหกรรม": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "วิทยาศาสตร์เครื่องสำอาง": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "อาชีวอนามัยและความปลอดภัย": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "เทคโนโลยีเคมี": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "วิทยาการคอมพิวเตอร์": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "คณิตศาสตร์": "หลักสูตรศึกษาศาสตรบัณฑิต",
                "ฟิสิกส์": "หลักสูตรศึกษาศาสตรบัณฑิต",
                "ความมั่นคงปลอดภัยไซเบอร์": "หลักสูตรวิทยาศาสตรบัณฑิต",
            },
            "คณะวิทยาการจัดการ": {
                "การบัญชี": "หลักสูตรบัญชีบัณฑิต",
                "การเงิน": "หลักสูตรบริหารธุรกิจบัณฑิต",
                "การตลาด": "หลักสูตรบริหารธุรกิจบัณฑิต",
                "การจัดการ": "หลักสูตรการจัดการบัณฑิต",
                "การบริการลูกค้า": "หลักสูตรบริหารธุรกิจบัณฑิต",
                "การจัดการธุรกิจค้าปลีก": "หลักสูตรบริหารธุรกิจบัณฑิต",
                "การจัดการทรัพยากรมนุษย์": "หลักสูตรบริหารธุรกิจบัณฑิต",
                "คอมพิวเตอร์ธุรกิจ": "หลักสูตรบริหารธุรกิจบัณฑิต",
                "ธุรกิจระหว่างประเทศ": "หลักสูตรบริหารธุรกิจบัณฑิต",
                "นิเทศศาสตร์": "หลักสูตรนิเทศศาสตรบัณฑิต",
                "เลขานุการทางการแพทย์": "หลักสูตรบริหารธุรกิจบัณฑิต",
            },
            "คณะมนุษยศาสตร์และสังคมศาสตร์": {
                "ภาษาอังกฤษธุรกิจ": "หลักสูตรศิลปศาสตรบัณฑิต",
                "จิตวิทยาอุตสาหกรรมและองค์การ": "หลักสูตรศิลปศาสตรบัณฑิต",
                "ภาษาไทย": "หลักสูตรศิลปศาสตรบัณฑิต",
                "ภาษาอังกฤษ": "หลักสูตรศิลปศาสตรบัณฑิต",
                "ภาษาจีน": "หลักสูตรศิลปศาสตรบัณฑิต",
                "ศิลปศึกษา": "หลักสูตรศึกษาศาสตรบัณฑิต",
                "บรรณารักษศาสตร์และสารสนเทศศาสตร์": "หลักสูตรศิลปศาสตรบัณฑิต",
                "นิติศาสตร์": "หลักสูตรนิติศาสตรบัณฑิต",
            },
            "คณะพยาบาลศาสตร์": {
                "พยาบาลศาสตร์": "หลักสูตรพยาบาลศาสตรบัณฑิต"
            },
            "โรงเรียนการท่องเที่ยวและการบริการ": {
                "การท่องเที่ยว": "หลักสูตรศิลปศาสตรบัณฑิต",
                "ธุรกิจการโรงแรม": "หลักสูตรศิลปศาสตรบัณฑิต",
                "ธุรกิจการบิน": "หลักสูตรศิลปศาสตรบัณฑิต",
                "ออกแบบนิทรรศการและการแสดง": "หลักสูตรศิลปศาสตรบัณฑิต",
                "การจัดการงานบริการ (นานาชาติ)": "หลักสูตรศิลปศาสตรบัณฑิต",
            },
            "โรงเรียนการเรือน": {
                "เทคโนโลยีการแปรรูปอาหาร": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "เทคโนโลยีการประกอบอาหารและบริการ": "หลักสูตรวิทยาศาสตรบัณฑิต",
                "คหกรรมศาสตร์": "หลักสูตรศิลปศาสตรบัณฑิต",
                "โภชนการและการประกอบอาหาร": "หลักสูตรวิทยาศาสตรบัณฑิต",
            }
        };

        const facultySelect = document.getElementById("faculty");
        const majorSelect = document.getElementById("major");
        const programSelect = document.getElementById("program");

        const facultyChoices = new Choices(facultySelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาคณะ..."
        });
        const majorChoices = new Choices(majorSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาสาขา..."
        });
        const programChoices = new Choices(programSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาหลักสูตร..."
        });

        // ===== Precomputed lists (from facultyMajorsPrograms only)
        const allFaculties = Object.keys(facultyMajorsPrograms);
        const allMajors = Object.values(facultyMajorsPrograms)
            .flatMap(majorsObj => Object.keys(majorsObj));
        const allPrograms = [...new Set(
            Object.values(facultyMajorsPrograms)
            .flatMap(majorsObj => Object.values(majorsObj))
        )];

        // Optional: major -> faculty mapping (derived only from facultyMajorsPrograms)
        const majorToFaculty = (() => {
            const map = {};
            for (const [faculty, majors] of Object.entries(facultyMajorsPrograms)) {
                for (const major of Object.keys(majors)) map[major] = faculty;
            }
            return map;
        })();

        // ===== Populate helpers
        const populateFaculties = (list) => {
            facultyChoices.clearStore();
            facultyChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกคณะ-",
                    selected: true,
                    disabled: false
                }]
                .concat(list.map(f => ({
                    value: f,
                    label: f
                }))),
                "value", "label", true
            );
        };
        const populateMajors = (list) => {
            majorChoices.clearStore();
            majorChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกสาขา-",
                    selected: true,
                    disabled: false
                }]
                .concat(list.map(m => ({
                    value: m,
                    label: m
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
                .concat(list.map(p => ({
                    value: p,
                    label: p
                }))),
                "value", "label", true
            );
        };

        // Init dropdowns
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

        // ===== Events
        // Faculty -> filter majors/programs
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

        // Major -> auto-select faculty & program
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

        // Program -> filter faculties & majors
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
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาจังหวัด..."
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

        const academicYears = [
            "2568",
            "2567",
            "2566",
            "2565",
            "2564"
        ];

        const academicYearSelect = document.getElementById("academic-year");

        const academicYearChoices = new Choices(academicYearSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาปีการศึกษา..."
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

        populateAcademicYears(academicYears);

        if (selectedAcademicYear) {
            academicYearChoices.setChoiceByValue(selectedAcademicYear);
        }

    });
</script>