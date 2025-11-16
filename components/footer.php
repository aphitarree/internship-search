<!-- FOOTER -->
<footer class="text-gray-800 dark:text-gray-200 bg-neutral-800 py-8">
    <div class="max-w-screen-xl mx-auto flex flex-col lg:flex-row lg:justify-start gap-5">
        <!-- ข้อความรายละเอียด (ซ้าย) -->
        <div class="lg:w-[38%] space-y-3 text-center lg:text-left">
            <p>
                <strong class="text-lg font-semibold text-red-700 dark:text-sky-400">
                    สำนักส่งเสริมวิชาการและงานทะเบียน
                </strong>
                <br>
                <strong class="text-sm tracking-wide uppercase text-stone-950 dark:text-gray-400">
                    THE OFFICE OF ACADEMIC PROMOTION AND REGISTRATION
                </strong>
            </p>

            <!-- University, Address -->
            <p class="text-sm leading-relaxed">
            <address class="not-italic">
                มหาวิทยาลัยสวนดุสิต<br>
                295 ถนนนครราชสีมา เขตดุสิต กรุงเทพฯ 10300<br>
                โทร. 02-244-5172-5<br>
            </address>
            e-mail:
            <a href="mailto:saraban-reg@dusit.ac.th"
                class="text-sky-600 hover:underline">
                saraban-reg@dusit.ac.th
            </a>
            <br>
            เปิดบริการ วันจันทร์ – ศุกร์ เวลา 08.30 – 16.30 น.
            </p>

            <!-- Social hyperlink icons -->
            <section class="flex gap-2">
                <!-- Facebook -->
                <a
                    href="https://www.facebook.com/regis.suandusit"
                    target="_blank"
                    class="text-gray-500 transition duration-200 transform hover:-translate-y-1
            border bg-white rounded-full p-2 inline-flex items-center justify-center">
                    <i class="text-2xl text-blue-600 fa-brands fa-facebook-f"></i>
                </a>

                <!-- YouTube -->
                <a
                    href="https://www.youtube.com/channel/UCNRXVo-ngomHQuvYJhCqO_g"
                    target="_blank"
                    class="text-gray-500 transition duration-200 transform hover:-translate-y-1
            border bg-white rounded-full p-2 inline-flex items-center justify-center">
                    <i class="text-2xl text-red-600 fa-brands fa-youtube"></i>
                </a>

                <!-- LINE -->
                <a
                    href="https://lin.ee/phdTWXn"
                    target="_blank"
                    class="text-gray-500 transition duration-200 transform hover:-translate-y-1 border bg-white rounded-full p-2 inline-flex items-center justify-center">
                    <i class="text-2xl text-green-600 fa-brands fa-line"></i>
                </a>

                <!-- Email -->
                <a
                    href="mailto:saraban-reg@dusit.ac.th"
                    class="text-gray-500 transition duration-200 transform hover:-translate-y-1 border bg-white rounded-full p-2 inline-flex items-center justify-center">
                    <i class="text-2xl text-sky-600 fa-solid fa-envelope"></i>
                </a>
            </section>

        </div>

        <!-- Form (ขวา) -->
        <div class="lg:w-1/2 max-w-lg w-full px-4 sm:pb-8 mx-auto lg:mx-0">
            <h2 class="text-xl font-semibold text-center">
                แบบประเมินความพึงพอใจ
            </h2>

            <div id="formMessage" class=" text-center"></div>

            <form id="feedbackForm" method="POST">

                <fieldset class="mb-1">
                    <legend class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        เนื้อหานี้มีประโยชน์หรือไม่? <span class="text-red-500">*</span>
                    </legend>

                    <div class="flex items-center gap-x-6 justify-center">
                        <div class="flex items-center">
                            <input id="useful_yes" name="is_useful" type="radio" value="มีประโยชน์" required
                                class="h-4 w-4 text-sky-600 focus:ring-sky-500 dark:bg-gray-700 dark:border-gray-600">
                            <label for="useful_yes" class="ml-2 text-sm">มีประโยชน์</label>
                        </div>

                        <div class="flex items-center">
                            <input id="useful_no" name="is_useful" type="radio" value="ไม่มีประโยชน์" required
                                class="h-4 w-4 text-sky-600 focus:ring-sky-500 dark:bg-gray-700 dark:border-gray-600">
                            <label for="useful_no" class="ml-2 text-sm">ไม่มีประโยชน์</label>
                        </div>
                    </div>
                </fieldset>

                <div class="mb-2">
                    <label for="comment" class="block text-sm font-medium">
                        ข้อเสนอแนะเพิ่มเติม (ไม่เกิน 200 ตัวอักษร)
                    </label>

                    <textarea id="comment" name="comment"
                        class="block w-full h-24 resize-none rounded-md border-gray-300 shadow-sm mt-1
                        focus:border-sky-500 focus:ring-sky-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600"
                        placeholder="  ความคิดเห็นของคุณ...">
                    </textarea>

                    <p id="charCount" class="mt-1 text-xs text-gray-500 dark:text-gray-400">0 / 200</p>
                </div>

                <button type="submit" id="submitButton"
                    class="w-full py-2 px-4 rounded-md shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 dark:bg-sky-500 dark:hover:bg-sky-600">
                    ส่งข้อเสนอแนะ
                </button>

            </form>
        </div>
    </div>

</footer>
<div class="bg-neutral-900 text-center py-3 text-sm text-gray-700 dark:text-gray-300">
    © <?= date('Y') ?> REGIS.DUSIT.AC.TH. ALL RIGHTS RESERVED.
</div>

<script>
    // --- ตัวนับจำนวนตัวอักษร ---
    const commentBox = document.getElementById('comment');
    const charCount = document.getElementById('charCount');

    commentBox.addEventListener('input', () => {
        charCount.textContent = `${commentBox.value.length} / 200`;
    });

    // --- AJAX Submit ---
    const form = document.getElementById('feedbackForm');
    const messageDiv = document.getElementById('formMessage');
    const submitBtn = document.getElementById('submitButton');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        messageDiv.textContent = 'กำลังส่งข้อมูล...';
        messageDiv.className = 'text-sky-600';
        submitBtn.disabled = true;
        submitBtn.textContent = 'กำลังส่ง...';

        const formData = new FormData(form);

        try {
            const response = await fetch('components/submit_feedback.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                messageDiv.textContent = result.message;
                messageDiv.className = 'text-green-600';
                form.reset();
                charCount.textContent = '0 / 200';
            } else {
                messageDiv.textContent = 'เกิดข้อผิดพลาด: ' + result.message;
                messageDiv.className = 'text-red-600';
            }

        } catch (err) {
            console.error(err); // log ลง console เพื่อ debug
            messageDiv.textContent = 'เกิดข้อผิดพลาด: ' + err.message;
            messageDiv.className = 'text-red-600';
        }

        submitBtn.disabled = false;
        submitBtn.textContent = 'ส่งข้อเสนอแนะ';
    });
</script>

</body>

</html>