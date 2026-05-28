
const {createApp, ref, onMounted} = Vue;

createApp({
    setup() {
        const pages = ref([]);
        const pageContainers = ref([]);
        let source_content = "";
        const MAX_PAGE_HEIGHT_PX = 650;

        function addPageRef(el, idx) {
            if (el) pageContainers.value[idx] = el;
        }

        function changePage(pageIdx) {
            const container = pageContainers.value[pageIdx];
            if (!container) return;
            const self_content = container.innerHTML;
            if (pages.value[pageIdx] !== self_content) {
                updateContent(pageIdx, self_content);
            }
        }

        function updateContent(pageIdx, newContent) {
            source_content = "";
            for (let i = 0; i < pages.value.length; i++) {
                source_content += (i === pageIdx) ? newContent : pages.value[i];
            }
            processContent();
        }

        function combineTables(tables) {
            if (!tables.length) return null;
            let first = tables[0];
            let tr_thead = first.getElementsByTagName('tr')[0];
            let table = document.createElement("table");
            table.className = first.className;
            table.width = first.width;
            table.style = first.style;
            let tbody = document.createElement("tbody");
            tbody.appendChild(tr_thead.cloneNode(true));
            for (let i = 0; i < tables.length; i++) {
                let tr_s = tables[i].getElementsByTagName('tr');
                for (let j = 1; j < tr_s.length; j++) {
                    tbody.appendChild(tr_s[j].cloneNode(true));
                }
            }
            table.innerHTML = tbody.outerHTML;
            return table;
        }

        function serializerSourceContent() {
            let tmp = document.createElement("div");
            tmp.innerHTML = source_content;
            let tables = tmp.getElementsByClassName("members");
            if (tables.length > 0) {
                let combined = combineTables(tables);
                for (let i = 0; i < tables.length; i++) {
                    if (i === 0) tables[i].innerHTML = combined.innerHTML;
                    else tables[i].remove();
                }
                let thead_s = tmp.getElementsByClassName("thead")
                for (let i = 1; i < thead_s.length; i++) {
                    thead_s[i].remove()
                }
                source_content = tmp.innerHTML;
            }
            let textarea = document.getElementById('content-f');
            textarea.value = source_content;
        }

        function splitTable(table, height_remains) {
            let tables = [];
            let thead_tr = table.getElementsByTagName('tr')[0];

            function createTable(tr_s, hide_head) {
                let example = document.createElement("table");
                example.className = table.className;
                example.style = table.style;
                example.width = table.width;
                let tbody = document.createElement("tbody");
                let tr = thead_tr.cloneNode(true);
                if (hide_head) tr.style.visibility = 'collapse';
                tbody.appendChild(tr);
                for (let i = 0; i < tr_s.length; i++) {
                    tbody.innerHTML += tr_s[i].outerHTML;
                }
                example.innerHTML = tbody.outerHTML;
                let thead_s = example.getElementsByClassName("thead")
                for (let i = 1; i < thead_s.length; i++) {
                    thead_s[i].remove()
                }

                return example;
            }

            let tr_s = table.getElementsByTagName('tr');
            let arr = [[]];
            let idx = 0;
            let tmp_height = height_remains;
            for (let i = 0; i < tr_s.length; i++) {
                let tr = tr_s[i];
                let height = tr.getBoundingClientRect().height;
                if (tmp_height - height > 0) {
                    tmp_height -= height;
                    arr[idx].push(tr);
                } else {
                    idx++;
                    arr[idx] = [tr];
                    tmp_height = 650;
                }
            }

            for (let i = 0; i < arr.length; i++) {
                tables.push(createTable(arr[i], i > 0));
            }
            return [tables, tmp_height];
        }

        function getLastTr(page) {
            let tmp = document.createElement("div");
            tmp.innerHTML = page;
            let table = tmp.getElementsByClassName('members')[0];
            if (!table) return [tmp, null];
            let tr_s = table.getElementsByTagName('tr');
            if (tr_s.length < 2) return [tmp, null];
            let last_tr = tr_s[tr_s.length - 1];
            tr_s[tr_s.length - 1].remove();
            let div = document.createElement("div");
            let new_table = div.appendChild(table.cloneNode(false));
            let tbody = document.createElement("tbody");
            tbody.appendChild(tr_s[0].cloneNode(true));
            tbody.appendChild(last_tr.cloneNode(true));
            new_table.appendChild(tbody.cloneNode(true));
            new_table.getElementsByTagName('tr')[0].style.visibility = 'collapse';
            return [tmp, div];
        }

        function splitContent() {
            let arr = [""];
            const tmp = document.createElement('div');
            tmp.innerHTML = source_content;
            tmp.style.cssText = 'width: 290mm; visibility: hidden; position: absolute;';
            document.body.appendChild(tmp);
            const elements = tmp.children;
            let max_height = 650;
            let page_idx = 0;

            for (let i = 0; i < elements.length; i++) {
                let el = elements[i];
                let height = el.getBoundingClientRect().height;
                if (max_height - height > 0) {
                    arr[page_idx] += el.outerHTML;
                    max_height -= height;
                } else if (el.className === "members") {
                    let result = splitTable(el, max_height);
                    let tables = result[0];
                    for (let j = 0; j < tables.length; j++) {
                        arr[page_idx] += tables[j].outerHTML;
                        if (j === tables.length - 1) {
                            max_height = result[1];
                            break;
                        }
                        page_idx++;
                        arr[page_idx] = "";
                    }
                } else {
                    page_idx++;
                    arr[page_idx] = "";
                    if (el.className === 'signature' && !arr[page_idx].includes('members')) {
                        let result = getLastTr(arr[page_idx - 1]);
                        arr[page_idx - 1] = result[0].innerHTML;
                        if (result[1]) arr[page_idx] += result[1].innerHTML;
                    }
                    arr[page_idx] += el.outerHTML;
                    max_height = 650 - height;
                }
            }
            document.body.removeChild(tmp);
            pages.value = arr.filter(page => page.trim() !== "");
        }

        function processContent() {
            serializerSourceContent();
            splitContent();
        }

        function initWithContent(content) {
            source_content = content;
            processContent();
        }

        function createSourceDiv() {

        }

        onMounted(() => {
            const initialContent = window.INITIAL_CONTENT || "";
            initWithContent(initialContent);
        });

        return {
            pages,
            addPageRef,
            changePage
        };
    }
}).mount('#app');
