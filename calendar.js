class Calendar {
    constructor(firstDayOfWeek, dateStr, onSelected = null, onClose = null) {
        // Member variables
        this.activeDiv = null;
        this.currentDateEl = null;
        this.getDateStatus = null;
        this.getDateToolTip = null;
        this.getDateText = null;
        this.timeout = null;
        this.onSelected = onSelected;
        this.onClose = onClose;
        this.dragging = false;
        this.hidden = false;
        this.dateClicked = false;

        // Configuração de data
        this.minYear = 1970;
        this.maxYear = 2050;
        this.dateFormat = Calendar._TT["DEF_DATE_FORMAT"];
        this.ttDateFormat = Calendar._TT["TT_DATE_FORMAT"];
        this.isPopup = true;
        this.weekNumbers = true;
        this.firstDayOfWeek = Number.isInteger(firstDayOfWeek) ? firstDayOfWeek : Calendar._FD;
        this.showsOtherMonths = false;
        this.dateStr = dateStr;
        this.ar_days = null;
        this.showsTime = false;
        this.time24 = true;
        this.yearStep = 2;
        this.hiliteToday = true;
        this.multiple = null;

        // HTML elements
        this.table = null;
        this.element = null;
        this.tbody = null;
        this.firstdayname = null;

        // Combo boxes
        this.monthsCombo = null;
        this.yearsCombo = null;
        this.hilitedMonth = null;
        this.activeMonth = null;
        this.hilitedYear = null;
        this.activeYear = null;

        // Inicializações únicas
        this.initStaticData();
    }

    static initStaticData() {
        if (typeof Calendar._SDN === "undefined") {
            Calendar._SDN_len = Calendar._SDN_len || 3;
            Calendar._SDN = Array.from({ length: 7 }, (_, i) => Calendar._DN[i].substr(0, Calendar._SDN_len));

            Calendar._SMN_len = Calendar._SMN_len || 3;
            Calendar._SMN = Array.from({ length: 12 }, (_, i) => Calendar._MN[i].substr(0, Calendar._SMN_len));
        }
    }
}

// ** CONSTANTS **

// "Static", needed for event handlers.
Calendar._C = null;

// Detect browser features
Calendar.is_ie = navigator.userAgentData 
    ? navigator.userAgentData.brands.some(brand => brand.brand === "Microsoft Edge" || brand.brand.includes("IE")) 
    : /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent);

Calendar.is_ie5 = Calendar.is_ie && /msie 5\.0/i.test(navigator.userAgent);
Calendar.is_opera = /opera/i.test(navigator.userAgent);
Calendar.is_khtml = /Konqueror|Safari|KHTML/i.test(navigator.userAgent);

// ** UTILITY FUNCTIONS **

Calendar.getAbsolutePos = function(el) {
    let x = 0, y = 0;
    
    while (el) {
        x += el.offsetLeft - (el.scrollLeft || 0);
        y += el.offsetTop - (el.scrollTop || 0);
        el = el.offsetParent;
    }
    
    return { x, y };
};

Calendar.isRelated = function(el, evt) {
    let related = evt.relatedTarget || (evt.type === "mouseover" ? evt.fromElement : evt.toElement);

    while (related) {
        if (related === el) return true;
        related = related.parentNode;
    }

    return false;
};

Calendar.removeClass = function(el, className) {
    el?.classList.remove(className);
};

// Adiciona uma classe ao elemento
Calendar.addClass = function (el, className) {
    el?.classList.add(className);
};

// Obtém o elemento associado ao evento
Calendar.getElement = function (ev) {
    return ev?.currentTarget?.closest("div");
};

// Obtém o elemento alvo do evento
Calendar.getTargetElement = function (ev) {
    return ev?.target?.closest("*");
};

// Interrompe a propagação de um evento e previne a ação padrão
Calendar.stopEvent = function (ev) {
    ev?.stopPropagation();
    ev?.preventDefault();
    return false;
};

// Adiciona um evento a um elemento
Calendar.addEvent = function (el, evname, func) {
    el?.addEventListener(evname, func, true);
};

// Remove um evento de um elemento
Calendar.removeEvent = function (el, evname, func) {
    el?.removeEventListener(evname, func, true);
};

// Cria um elemento e o anexa a um pai, se especificado
Calendar.createElement = function (type, parent) {
    const el = document.createElement(type);
    parent?.appendChild(el);
    return el;
};

// Adiciona eventos para fazer um elemento se comportar como um botão
Calendar._add_evs = function (el) {
    Calendar.addEvent(el, "mouseover", Calendar.dayMouseOver);
    Calendar.addEvent(el, "mousedown", Calendar.dayMouseDown);
    Calendar.addEvent(el, "mouseout", Calendar.dayMouseOut);

    if (Calendar.is_ie) {
        Calendar.addEvent(el, "dblclick", Calendar.dayMouseDblClick);
        el.setAttribute("unselectable", "true");
    }
};

// Encontra o mês associado a um elemento
Calendar.findMonth = function (el) {
    return el?.closest("[month]") || null;
};

// Encontra o ano associado a um elemento
Calendar.findYear = function (el) {
    return el?.closest("[year]") || null;
};

Calendar.showMonthsCombo = function () {
    const cal = Calendar._C;
    if (!cal) return false;

    const cd = cal.activeDiv;
    const mc = cal.monthsCombo;
    if (!mc) return false;

    Calendar.removeClass(cal.hilitedMonth, "hilite");
    Calendar.removeClass(cal.activeMonth, "active");

    const mon = mc.getElementsByTagName("div")[cal.date.getMonth()];
    Calendar.addClass(mon, "active");
    cal.activeMonth = mon;

    const { left, top, width, height } = cd.getBoundingClientRect();
    mc.style.display = "block";
    mc.style.left = cd.navtype < 0 ? `${left}px` : `${left + width - mc.offsetWidth || 50}px`;
    mc.style.top = `${top + height}px`;
};

Calendar.showYearsCombo = function (fwd) {
    const cal = Calendar._C;
    if (!cal) return false;

    const cd = cal.activeDiv;
    const yc = cal.yearsCombo;
    if (!yc) return false;

    Calendar.removeClass(cal.hilitedYear, "hilite");
    Calendar.removeClass(cal.activeYear, "active");

    let year = cal.date.getFullYear() + (fwd ? 1 : -1);
    let show = false;

    [...yc.children].forEach((yr) => {
        if (year >= cal.minYear && year <= cal.maxYear) {
            yr.textContent = year;
            yr.year = year;
            yr.style.display = "block";
            show = true;
        } else {
            yr.style.display = "none";
        }
        year += fwd ? cal.yearStep : -cal.yearStep;
    });

    if (show) {
        const { left, top, width, height } = cd.getBoundingClientRect();
        yc.style.display = "block";
        yc.style.left = cd.navtype < 0 ? `${left}px` : `${left + width - yc.offsetWidth || 50}px`;
        yc.style.top = `${top + height}px`;
    }
};

// Manipulador de evento para soltar o mouse na tabela do calendário
Calendar.tableMouseUp = function (ev) {
    const cal = Calendar._C;
    if (!cal) return false;

    if (cal.timeout) clearTimeout(cal.timeout);

    const el = cal.activeDiv;
    if (!el) return false;

    const target = Calendar.getTargetElement(ev);
    Calendar.removeClass(el, "active");

    if (target === el || target.parentNode === el) {
        Calendar.cellClick(el, ev);
    }

    const mon = target.closest("[month]");
    if (mon) {
        const date = new Date(cal.date);
        if (mon.month !== date.getMonth()) {
            date.setMonth(mon.month);
            cal.setDate(date);
            cal.dateClicked = false;
            cal.callHandler();
        }
    } else {
        const year = target.closest("[year]");
        if (year) {
            const date = new Date(cal.date);
            if (year.year !== date.getFullYear()) {
                date.setFullYear(year.year);
                cal.setDate(date);
                cal.dateClicked = false;
                cal.callHandler();
            }
        }
    }

    document.removeEventListener("mouseup", Calendar.tableMouseUp);
    document.removeEventListener("mouseover", Calendar.tableMouseOver);
    document.removeEventListener("mousemove", Calendar.tableMouseOver);
    
    cal._hideCombos();
    Calendar._C = null;

    return Calendar.stopEvent(ev);
};

Calendar.tableMouseOver = function (ev) {
    const cal = Calendar._C;
    if (!cal) return;

    const el = cal.activeDiv;
    const target = Calendar.getTargetElement(ev);

    if (target === el || target.parentNode === el) {
        Calendar.addClass(el, "hilite active");
        Calendar.addClass(el.parentNode, "rowhilite");
    } else {
        if (!el.navtype || (el.navtype !== 50 && (el.navtype === 0 || Math.abs(el.navtype) > 2))) {
            Calendar.removeClass(el, "active");
        }
        Calendar.removeClass(el, "hilite");
        Calendar.removeClass(el.parentNode, "rowhilite");
    }

    ev = ev || window.event;

    if (el.navtype === 50 && target !== el) {
        const { left, width } = el.getBoundingClientRect();
        const x = ev.clientX;
        let dx = Math.abs(x - (x > left + width ? left + width : left));

        const range = el._range;
        let i = range.indexOf(el._current);
        const count = Math.floor(dx / 10) % range.length;

        for (let j = 0; j < count; j++) {
            i = x > left + width ? (i + 1) % range.length : (i - 1 + range.length) % range.length;
        }

        el.innerHTML = range[i];
        cal.onUpdateTime();
    }

    const mon = target.closest("[month]");
    if (mon) {
        if (mon.month !== cal.date.getMonth()) {
            Calendar.removeClass(cal.hilitedMonth, "hilite");
            Calendar.addClass(mon, "hilite");
            cal.hilitedMonth = mon;
        }
    } else {
        Calendar.removeClass(cal.hilitedMonth, "hilite");
        cal.hilitedMonth = null;

        const year = target.closest("[year]");
        if (year) {
            if (year.year !== cal.date.getFullYear()) {
                Calendar.removeClass(cal.hilitedYear, "hilite");
                Calendar.addClass(year, "hilite");
                cal.hilitedYear = year;
            }
        } else {
            Calendar.removeClass(cal.hilitedYear, "hilite");
            cal.hilitedYear = null;
        }
    }

    return Calendar.stopEvent(ev);
};

Calendar.tableMouseDown = function (ev) {
    if (Calendar.getTargetElement(ev) === Calendar.getElement(ev)) {
        return Calendar.stopEvent(ev);
    }
};

Calendar.calDragIt = function (ev) {
    const cal = Calendar._C;
    if (!(cal && cal.dragging)) return false;

    const posX = Calendar.is_ie ? window.event.clientX + document.body.scrollLeft : ev.pageX;
    const posY = Calendar.is_ie ? window.event.clientY + document.body.scrollTop : ev.pageY;

    cal.hideShowCovered();
    cal.element.style.left = `${posX - cal.xOffs}px`;
    cal.element.style.top = `${posY - cal.yOffs}px`;

    return Calendar.stopEvent(ev);
};

Calendar.calDragEnd = function (ev) {
    const cal = Calendar._C;
    if (!cal) return false;

    cal.dragging = false;

    document.removeEventListener("mousemove", Calendar.calDragIt);
    document.removeEventListener("mouseup", Calendar.calDragEnd);
    
    Calendar.tableMouseUp(ev);
    cal.hideShowCovered();
};

Calendar.dayMouseDown = function (ev) {
    const el = Calendar.getElement(ev);
    if (el.disabled) return false;

    const cal = el.calendar;
    cal.activeDiv = el;
    Calendar._C = cal;

    if (el.navtype !== 300) {
        if (el.navtype === 50) {
            el._current = el.innerHTML;
            Calendar.addEvent(document, "mousemove", Calendar.tableMouseOver);
        } else {
            Calendar.addEvent(document, Calendar.is_ie5 ? "mousemove" : "mouseover", Calendar.tableMouseOver);
        }
        Calendar.addClass(el, "hilite active");
        Calendar.addEvent(document, "mouseup", Calendar.tableMouseUp);
    } else if (cal.isPopup) {
        cal._dragStart(ev);
    }

    if ([-1, 1].includes(el.navtype)) {
        clearTimeout(cal.timeout);
        cal.timeout = setTimeout(Calendar.showMonthsCombo, 250);
    } else if ([-2, 2].includes(el.navtype)) {
        clearTimeout(cal.timeout);
        cal.timeout = setTimeout(() => Calendar.showYearsCombo(el.navtype > 0), 250);
    } else {
        cal.timeout = null;
    }

    return Calendar.stopEvent(ev);
};

Calendar.dayMouseDblClick = function (ev) {
    Calendar.cellClick(Calendar.getElement(ev), ev || window.event);
    if (Calendar.is_ie) document.selection.empty();
};

Calendar.dayMouseOver = function (ev) {
    const el = Calendar.getElement(ev);
    if (Calendar.isRelated(el, ev) || Calendar._C || el.disabled) return false;

    if (el.ttip) {
        if (el.ttip.startsWith("_")) {
            el.ttip = el.caldate.print(el.calendar.ttDateFormat) + el.ttip.substring(1);
        }
        el.calendar.tooltips.innerHTML = el.ttip;
    }

    if (el.navtype !== 300) {
        Calendar.addClass(el, "hilite");
        if (el.caldate) Calendar.addClass(el.parentNode, "rowhilite");
    }
    return Calendar.stopEvent(ev);
};

Calendar.dayMouseOut = function (ev) {
    const el = Calendar.getElement(ev);
    if (Calendar.isRelated(el, ev) || Calendar._C || el.disabled) return false;

    Calendar.removeClass(el, "hilite");
    if (el.caldate) Calendar.removeClass(el.parentNode, "rowhilite");
    if (el.calendar) el.calendar.tooltips.innerHTML = Calendar._TT["SEL_DATE"];

    return Calendar.stopEvent(ev);
};

Calendar.cellClick = function (el, ev) {
    const cal = el.calendar;
    let closing = false, newdate = false;
    let date = new Date(cal.date);

    if (typeof el.navtype === "undefined") {
        if (cal.currentDateEl) {
            Calendar.removeClass(cal.currentDateEl, "selected");
            Calendar.addClass(el, "selected");
            closing = cal.currentDateEl === el;
            if (!closing) cal.currentDateEl = el;
        }
        cal.date.setDateOnly(el.caldate);
        date = cal.date;
        const other_month = !(cal.dateClicked = !el.otherMonth);
        if (!other_month && !cal.currentDateEl) {
            cal._toggleMultipleDate(new Date(date));
        } else {
            newdate = !el.disabled;
        }
        if (other_month) cal._init(cal.firstDayOfWeek, date);
    } else {
        if (el.navtype === 200) {
            Calendar.removeClass(el, "hilite");
            cal.callCloseHandler();
            return;
        }
        switch (el.navtype) {
            case 400:
                Calendar.removeClass(el, "hilite");
                alert(Calendar._TT["ABOUT"] || "Help text not translated");
                return;
            case -2:
                if (date.getFullYear() > cal.minYear) date.setFullYear(date.getFullYear() - 1);
                break;
            case -1:
                if (date.getMonth() > 0) {
                    date.setMonth(date.getMonth() - 1);
                } else if (date.getFullYear() > cal.minYear) {
                    date.setFullYear(date.getFullYear() - 1);
                    date.setMonth(11);
                }
                break;
            case 1:
                if (date.getMonth() < 11) {
                    date.setMonth(date.getMonth() + 1);
                } else if (date.getFullYear() < cal.maxYear) {
                    date.setFullYear(date.getFullYear() + 1);
                    date.setMonth(0);
                }
                break;
            case 2:
                if (date.getFullYear() < cal.maxYear) date.setFullYear(date.getFullYear() + 1);
                break;
            case 100:
                cal.setFirstDayOfWeek(el.fdow);
                return;
            case 50:
                const range = el._range;
                let index = range.indexOf(el.innerHTML);
                index = ev?.shiftKey ? (index - 1 + range.length) % range.length : (index + 1) % range.length;
                el.innerHTML = range[index];
                cal.onUpdateTime();
                return;
            case 0:
                if (typeof cal.getDateStatus === "function" && cal.getDateStatus(date, date.getFullYear(), date.getMonth(), date.getDate())) {
                    return false;
                }
                break;
        }
        if (!date.equalsTo(cal.date)) {
            cal.setDate(date);
            newdate = true;
        } else if (el.navtype === 0) {
            newdate = closing = true;
        }
    }
    if (newdate) ev && cal.callHandler();
    if (closing) {
        Calendar.removeClass(el, "hilite");
        ev && cal.callCloseHandler();
    }
};

Calendar.prototype.create = function (_par) {
    const parent = _par || document.body;
    this.isPopup = !_par;
    this.date = this.dateStr ? new Date(this.dateStr) : new Date();

    this.element = this.createCalendarElement();
    this.table = this.createTable();
    this.element.appendChild(this.table);
    
    const thead = this.createHeader();
    this.table.appendChild(thead);
    
    this.tbody = this.createBody();
    this.table.appendChild(this.tbody);

    if (this.showsTime) this.createTimeSelector();
    
    const tfoot = this.createFooter();
    this.table.appendChild(tfoot);
    
    this.monthsCombo = this.createCombo(Calendar._SMN, 'monthsCombo');
    this.yearsCombo = this.createCombo(Array(12).fill(''), 'yearsCombo');
    
    this._init(this.firstDayOfWeek, this.date);
    parent.appendChild(this.element);
};

Calendar.prototype.createCalendarElement = function () {
    const div = Calendar.createElement("div");
    div.className = "calendar";
    if (this.isPopup) {
        Object.assign(div.style, { position: "absolute", display: "none" });
    }
    return div;
};

Calendar.prototype.createTable = function () {
    const table = Calendar.createElement("table");
    Object.assign(table, { cellSpacing: 0, cellPadding: 0, calendar: this });
    Calendar.addEvent(table, "mousedown", Calendar.tableMouseDown);
    return table;
};

Calendar.prototype.createHeader = function () {
    const thead = Calendar.createElement("thead");
    const row = Calendar.createElement("tr", thead);
    
    this.title = this.createHeaderCell("", this.isPopup ? 5 : 6, 300, "title");
    if (this.isPopup) this.title.style.cursor = "move";
    
    row.append(
        this.createHeaderCell("?", 1, 400, "button"),
        this.title,
        this.isPopup ? this.createHeaderCell("&#x00d7;", 1, 200, "button") : null
    );
    return thead;
};

Calendar.prototype.createHeaderCell = function (text, colSpan, navtype, className = "button") {
    const cell = Calendar.createElement("td");
    Object.assign(cell, { colSpan, className: className + (Math.abs(navtype) <= 2 ? " nav" : "") });
    cell.innerHTML = `<div unselectable='on'>${text}</div>`;
    cell.calendar = this;
    cell.navtype = navtype;
    Calendar._add_evs(cell);
    return cell;
};

Calendar.prototype.createBody = function () {
    const tbody = Calendar.createElement("tbody");
    for (let i = 0; i < 6; i++) {
        const row = Calendar.createElement("tr", tbody);
        for (let j = 0; j < 7; j++) {
            const cell = Calendar.createElement("td", row);
            cell.calendar = this;
            Calendar._add_evs(cell);
        }
    }
    return tbody;
};

Calendar.prototype.createFooter = function () {
    const tfoot = Calendar.createElement("tfoot");
    const row = Calendar.createElement("tr", tfoot);
    row.className = "footrow";
    
    this.tooltips = this.createHeaderCell(Calendar._TT["SEL_DATE"], this.weekNumbers ? 8 : 7, 300, "ttip");
    row.appendChild(this.tooltips);
    return tfoot;
};

Calendar.prototype.createCombo = function (items, className) {
    const div = Calendar.createElement("div", this.element);
    div.className = "combo";
    items.forEach((item, index) => {
        const label = Calendar.createElement("div");
        label.className = Calendar.is_ie ? "label-IEfix" : "label";
        label.month = index;
        label.innerHTML = item;
        div.appendChild(label);
    });
    return div;
};

/** Keyboard navigation, only for popup calendars */
Calendar._keyEvent = function (ev) {
    var cal = window._dynarch_popupCalendar;
    if (!cal || cal.multiple) return false;
    
    if (Calendar.is_ie) ev = window.event;
    var act = Calendar.is_ie || ev.type === "keypress",
        key = ev.keyCode;
    
    if (ev.ctrlKey) {
        return handleCtrlKey(cal, key, act);
    } else {
        return handleRegularKey(cal, key, act, ev);
    }
};

function handleCtrlKey(cal, key, act) {
    const actions = {
        37: cal._nav_pm, // Left Arrow (Previous Month)
        38: cal._nav_py, // Up Arrow (Previous Year)
        39: cal._nav_nm, // Right Arrow (Next Month)
        40: cal._nav_ny  // Down Arrow (Next Year)
    };
    
    if (actions[key] && act) {
        Calendar.cellClick(actions[key]);
        return Calendar.stopEvent(event);
    }
    return false;
}

function handleRegularKey(cal, key, act, ev) {
    const actions = {
        32: cal._nav_now, // Space (Go to Today)
        27: () => act && cal.callCloseHandler(), // Escape (Close Calendar)
        13: () => act && Calendar.cellClick(cal.currentDateEl, ev) // Enter (Select Date)
    };
    
    if (actions[key]) {
        if (typeof actions[key] === "function") {
            actions[key]();
        } else {
            Calendar.cellClick(actions[key]);
        }
        return Calendar.stopEvent(ev);
    }
    
    if ([37, 38, 39, 40].includes(key) && act) {
        return navigateCalendar(cal, key);
    }
    
    return false;
}

function navigateCalendar(cal, key) {
    let prev = key === 37 || key === 38, // Left or Up
        step = key === 37 || key === 39 ? 1 : 7, // 1 for Left/Right, 7 for Up/Down
        x, y, ne, el;
    
    function setVars() {
        el = cal.currentDateEl;
        var p = el.pos;
        x = p & 15;
        y = p >> 4;
        ne = cal.ar_days[y][x];
    }
    
    function adjustDate(offset) {
        let date = new Date(cal.date);
        date.setDate(date.getDate() + offset);
        cal.setDate(date);
    }
    
    setVars();
    while (true) {
        switch (key) {
            case 37: // Left
                if (--x >= 0) ne = cal.ar_days[y][x];
                else { x = 6; key = 38; continue; }
                break;
            case 38: // Up
                if (--y >= 0) ne = cal.ar_days[y][x];
                else { adjustDate(-step); setVars(); }
                break;
            case 39: // Right
                if (++x < 7) ne = cal.ar_days[y][x];
                else { x = 0; key = 40; continue; }
                break;
            case 40: // Down
                if (++y < cal.ar_days.length) ne = cal.ar_days[y][x];
                else { adjustDate(step); setVars(); }
                break;
        }
        break;
    }
    
    if (ne && !ne.disabled) {
        Calendar.cellClick(ne);
    } else {
        prev ? adjustDate(-step) : adjustDate(step);
    }
    
    return Calendar.stopEvent(event);
}

/**
 * (RE)Initializes the calendar to the given date and firstDayOfWeek
 */
Calendar.prototype._init = function (firstDayOfWeek, date) {
    this.table.style.visibility = "hidden";
    this.firstDayOfWeek = firstDayOfWeek;
    this.date = new Date(date);
    
    this._adjustYear(date);
    this._computeFirstDisplayedDate(date);

    this._populateCalendar(date);
    
    this.title.innerHTML = `${Calendar._MN[this.date.getMonth()]}, ${this.date.getFullYear()}`;
    this.onSetTime();
    
    this.table.style.visibility = "visible";
    this._initMultipleDates();
};

/**
 * Adjusts the year of the given date within the allowed range.
 */
Calendar.prototype._adjustYear = function (date) {
    let year = date.getFullYear();
    if (year < this.minYear) {
        date.setFullYear(this.minYear);
    } else if (year > this.maxYear) {
        date.setFullYear(this.maxYear);
    }
};

/**
 * Computes the first day that will be displayed in the calendar.
 */
Calendar.prototype._computeFirstDisplayedDate = function (date) {
    date.setDate(1);
    let day1 = (date.getDay() - this.firstDayOfWeek + 7) % 7;
    date.setDate(-day1 + 1);
};

/**
 * Populates the calendar with days, handling different cases.
 */
Calendar.prototype._populateCalendar = function (date) {
    let today = new Date(),
        TY = today.getFullYear(),
        TM = today.getMonth(),
        TD = today.getDate();
    
    let year = this.date.getFullYear(),
        month = this.date.getMonth(),
        mday = this.date.getDate(),
        no_days = this.date.getMonthDays();

    let row = this.tbody.firstChild;
    let ar_days = (this.ar_days = []);
    let weekend = Calendar._TT["WEEKEND"];
    let dates = this.multiple ? (this.datesCells = {}) : null;

    for (let i = 0; i < 6; ++i, row = row.nextSibling) {
        let cell = row.firstChild;
        row.className = "daysrow";

        if (this.weekNumbers) {
            this._setWeekNumber(cell, date);
            cell = cell.nextSibling;
        }

        let hasDays = false;
        let dpos = (ar_days[i] = []);

        for (let j = 0; j < 7; ++j, cell = cell.nextSibling, date.setDate(date.getDate() + 1)) {
            let iday = date.getDate(),
                wday = date.getDay(),
                currentMonth = date.getMonth() === month;

            cell.className = "day";
            cell.pos = (i << 4) | j;
            dpos[j] = cell;

            this._configureCell(cell, date, year, month, iday, wday, currentMonth, hasDays, TY, TM, TD, dates, weekend);
        }

        if (!(hasDays || this.showsOtherMonths)) {
            row.className = "emptyrow";
        }
    }
};

/**
 * Sets the week number in the first column.
 */
Calendar.prototype._setWeekNumber = function (cell, date) {
    cell.className = "day wn";
    cell.innerHTML = date.getWeekNumber();
};

/**
 * Configures individual day cells, handling different states.
 */
Calendar.prototype._configureCell = function (cell, date, year, month, iday, wday, currentMonth, hasDays, TY, TM, TD, dates, weekend) {
    if (!currentMonth) {
        this._handleOtherMonthCell(cell);
    } else {
        cell.otherMonth = false;
        hasDays = true;
    }

    cell.disabled = false;
    cell.innerHTML = this.getDateText ? this.getDateText(date, iday) : iday;

    if (dates) {
        dates[date.print("%Y%m%d")] = cell;
    }

    this._applyDateStatus(cell, date, year, month, iday);

    if (!cell.disabled) {
        this._highlightSpecialDays(cell, date, iday, TY, TM, TD, currentMonth);
    }
};

/**
 * Handles cells that belong to other months.
 */
Calendar.prototype._handleOtherMonthCell = function (cell) {
    if (this.showsOtherMonths) {
        cell.className += " othermonth";
        cell.otherMonth = true;
    } else {
        cell.className = "emptycell";
        cell.innerHTML = "&nbsp;";
        cell.disabled = true;
    }
};

/**
 * Applies status and tooltip information to the cell.
 */
Calendar.prototype._applyDateStatus = function (cell, date, year, month, iday) {
    if (this.getDateStatus) {
        let status = this.getDateStatus(date, year, month, iday);

        if (this.getDateToolTip) {
            let toolTip = this.getDateToolTip(date, year, month, iday);
            if (toolTip) cell.title = toolTip;
        }

        if (status === true) {
            cell.className += " disabled";
            cell.disabled = true;
        } else {
            if (/disabled/i.test(status)) cell.disabled = true;
            cell.className += " " + status;
        }
    }
};

/**
 * Highlights special days like today, selected date, and weekends.
 */
Calendar.prototype._highlightSpecialDays = function (cell, date, iday, TY, TM, TD, currentMonth) {
    cell.caldate = new Date(date);
    cell.ttip = "_";

    if (!this.multiple && currentMonth && iday === this.date.getDate() && this.hiliteToday) {
        cell.className += " selected";
        this.currentDateEl = cell;
    }

    if (date.getFullYear() === TY && date.getMonth() === TM && iday === TD) {
        cell.className += " today";
        cell.ttip += Calendar._TT["PART_TODAY"];
    }

    if (Calendar._TT["WEEKEND"].includes(date.getDay().toString())) {
        cell.className += cell.otherMonth ? " oweekend" : " weekend";
    }
};

/**
 * Initializes selected multiple dates in the calendar.
 */
Calendar.prototype._initMultipleDates = function () {
    if (!this.multiple) return;

    for (const [dateStr, isSelected] of Object.entries(this.multiple)) {
        if (isSelected && this.datesCells[dateStr]) {
            this.datesCells[dateStr].classList.add("selected");
        }
    }
};

/**
 * Toggles selection for a specific date in multiple selection mode.
 */
Calendar.prototype._toggleMultipleDate = function (date) {
    if (!this.multiple) return;

    const dateStr = date.print("%Y%m%d");
    const cell = this.datesCells[dateStr];

    if (cell) {
        if (this.multiple[dateStr]) {
            Calendar.removeClass(cell, "selected");
            delete this.multiple[dateStr];
        } else {
            Calendar.addClass(cell, "selected");
            this.multiple[dateStr] = date;
        }
    }
};

/**
 * Sets a function to handle tooltips for dates.
 */
Calendar.prototype.setDateToolTipHandler = function (handler) {
    this.getDateToolTip = handler;
};

/**
 * Updates the calendar to a new date if it differs from the current one.
 */
Calendar.prototype.setDate = function (date) {
    if (!date.equalsTo(this.date)) {
        this._init(this.firstDayOfWeek, date);
    }
};

/**
 * Refreshes the calendar to reflect changes in disabled dates.
 */
Calendar.prototype.refresh = function () {
    this._init(this.firstDayOfWeek, this.date);
};

/**
 * Sets the first day of the week and updates the display.
 */
Calendar.prototype.setFirstDayOfWeek = function (firstDayOfWeek) {
    this._init(firstDayOfWeek, this.date);
    this._displayWeekdays();
};

/**
 * Sets a function to determine which dates should be disabled.
 */
Calendar.prototype.setDateStatusHandler = Calendar.prototype.setDisabledHandler = function (handler) {
    this.getDateStatus = handler;
};

/**
 * Sets the allowed year range for the calendar.
 */
Calendar.prototype.setRange = function (minYear, maxYear) {
    this.minYear = minYear;
    this.maxYear = maxYear;
};

/**
 * Calls the user-defined handler for selecting a date.
 */
Calendar.prototype.callHandler = function () {
    if (this.onSelected) {
        this.onSelected(this, this.date.print(this.dateFormat));
    }
};

/**
 * Calls the user-defined handler for closing the calendar.
 */
Calendar.prototype.callCloseHandler = function () {
    if (this.onClose) {
        this.onClose(this);
    }
    this.hideShowCovered();
};

/**
 * Removes the calendar element from the DOM and cleans up references.
 */
Calendar.prototype.destroy = function () {
    this.element.parentNode?.removeChild(this.element);
    Calendar._C = null;
    window._dynarch_popupCalendar = null;
};

/**
 * Moves the calendar element to a new parent in the DOM.
 */
Calendar.prototype.reparent = function (newParent) {
    if (!newParent) return;
    newParent.appendChild(this.element);
};

// Verifica se o clique foi fora do calendário e o fecha se necessário.
Calendar._checkCalendar = function(ev) {
    const calendar = window._dynarch_popupCalendar;
    if (!calendar) return false;

    const el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
    let parentEl = el;

    while (parentEl != null && parentEl != calendar.element) {
        parentEl = parentEl.parentNode;
    }

    if (parentEl == null) {
        window._dynarch_popupCalendar.callCloseHandler();
        return Calendar.stopEvent(ev);
    }
};

// Exibe o calendário.
Calendar.prototype.show = function () {
    const rows = this.table.getElementsByTagName("tr");
    Array.from(rows).forEach(row => {
        Calendar.removeClass(row, "rowhilite");
        const cells = row.getElementsByTagName("td");
        Array.from(cells).forEach(cell => {
            Calendar.removeClass(cell, "hilite");
            Calendar.removeClass(cell, "active");
        });
    });

    this.element.style.display = "block";
    this.hidden = false;

    if (this.isPopup) {
        window._dynarch_popupCalendar = this;
        Calendar.addEvent(document, "keydown", Calendar._keyEvent);
        Calendar.addEvent(document, "keypress", Calendar._keyEvent);
        Calendar.addEvent(document, "mousedown", Calendar._checkCalendar);
    }
    
    this.hideShowCovered();
};

// Esconde o calendário.
Calendar.prototype.hide = function () {
    if (this.isPopup) {
        Calendar.removeEvent(document, "keydown", Calendar._keyEvent);
        Calendar.removeEvent(document, "keypress", Calendar._keyEvent);
        Calendar.removeEvent(document, "mousedown", Calendar._checkCalendar);
    }

    this.element.style.display = "none";
    this.hidden = true;
    this.hideShowCovered();
};

// Exibe o calendário em uma posição absoluta.
Calendar.prototype.showAt = function (x, y) {
    this.element.style.left = `${x}px`;
    this.element.style.top = `${y}px`;
    this.show();
};

// Exibe o calendário perto de um elemento específico.
Calendar.prototype.showAtElement = function (el, opts) {
    const p = Calendar.getAbsolutePos(el);
    if (!opts || typeof opts !== "string") {
        this.showAt(p.x, p.y + el.offsetHeight);
        return true;
    }

    const fixPosition = (box) => {
        if (box.x < 0) box.x = 0;
        if (box.y < 0) box.y = 0;

        const cp = document.createElement("div");
        cp.style.position = "absolute";
        cp.style.right = cp.style.bottom = cp.style.width = cp.style.height = "0px";
        document.body.appendChild(cp);
        
        const br = Calendar.getAbsolutePos(cp);
        document.body.removeChild(cp);
        br.y += window.scrollY;
        br.x += window.scrollX;

        if (box.x + box.width - br.x > 0) box.x -= box.x + box.width - br.x;
        if (box.y + box.height - br.y > 0) box.y -= box.y + box.height - br.y;
    };

    this.element.style.display = "block";
    
    const adjustPosition = () => {
        const w = this.element.offsetWidth;
        const h = this.element.offsetHeight;
        this.element.style.display = "none";

        const valign = opts.charAt(0);
        const halign = opts.length > 1 ? opts.charAt(1) : "l";

        switch (valign) {
            case "T": p.y -= h; break;
            case "B": p.y += el.offsetHeight; break;
            case "C": p.y += (el.offsetHeight - h) / 2; break;
            case "t": p.y += el.offsetHeight - h; break;
            case "b": break; // já está na posição correta
        }

        switch (halign) {
            case "L": p.x -= w; break;
            case "R": p.x += el.offsetWidth; break;
            case "C": p.x += (el.offsetWidth - w) / 2; break;
            case "l": p.x += el.offsetWidth - w; break;
            case "r": break; // já está na posição correta
        }

        p.width = w;
        p.height = h + 40;
        this.monthsCombo.style.display = "none";
        fixPosition(p);
        this.showAt(p.x, p.y);
    };

    if (Calendar.is_khtml) {
        setTimeout(adjustPosition, 10);
    } else {
        adjustPosition();
    }
};

// Personaliza o formato de data.
Calendar.prototype.setDateFormat = function (str) {
    this.dateFormat = str;
};

// Personaliza o formato de data na tooltip.
Calendar.prototype.setTtDateFormat = function (str) {
    this.ttDateFormat = str;
};

/**
 * Tenta identificar a data representada em uma string. Se bem-sucedido, também
 * chama this.setDate para mover o calendário para a data fornecida.
 */
Calendar.prototype.parseDate = function(str, fmt = this.dateFormat) {
    this.setDate(Date.parseDate(str, fmt));
};

/**
 * Mostra ou esconde os elementos cobertos pelo calendário.
 */
Calendar.prototype.hideShowCovered = function () {
    if (!Calendar.is_ie && !Calendar.is_opera) return;

    const getVisib = (obj) => {
        let value = obj.style.visibility;
        if (!value) {
            if (document.defaultView && typeof (document.defaultView.getComputedStyle) === "function") {
                value = !Calendar.is_khtml 
                    ? document.defaultView.getComputedStyle(obj, "").getPropertyValue("visibility") 
                    : '';
            } else if (obj.currentStyle) { // IE
                value = obj.currentStyle.visibility;
            } else {
                value = '';
            }
        }
        return value;
    };

    const tags = ["applet", "iframe", "select"];
    let el = this.element;
    let { x: EX1, y: EY1 } = Calendar.getAbsolutePos(el);
    let EX2 = el.offsetWidth + EX1;
    let EY2 = el.offsetHeight + EY1;

    tags.forEach(tag => {
        const elements = document.getElementsByTagName(tag);
        Array.from(elements).forEach(cc => {
            let { x: CX1, y: CY1 } = Calendar.getAbsolutePos(cc);
            let CX2 = cc.offsetWidth + CX1;
            let CY2 = cc.offsetHeight + CY1;

            if (this.hidden || (CX1 > EX2) || (CX2 < EX1) || (CY1 > EY2) || (CY2 < EY1)) {
                if (!cc.__msh_save_visibility) {
                    cc.__msh_save_visibility = getVisib(cc);
                }
                cc.style.visibility = cc.__msh_save_visibility;
            } else {
                if (!cc.__msh_save_visibility) {
                    cc.__msh_save_visibility = getVisib(cc);
                }
                cc.style.visibility = "hidden";
            }
        });
    });
};

/** Função interna: exibe a barra com os nomes dos dias da semana. */
Calendar.prototype._displayWeekdays = function () {
    const fdow = this.firstDayOfWeek;
    let cell = this.firstdayname;
    const weekend = Calendar._TT["WEEKEND"];

    Array.from({ length: 7 }).forEach((_, i) => {
        const realday = (i + fdow) % 7;
        cell.className = "day name";

        if (i) {
            cell.ttip = Calendar._TT["DAY_FIRST"].replace("%s", Calendar._DN[realday]);
            cell.navtype = 100;
            cell.calendar = this;
            cell.fdow = realday;
            Calendar._add_evs(cell);
        }

        if (weekend.indexOf(realday.toString()) !== -1) {
            Calendar.addClass(cell, "weekend");
        }

        cell.innerHTML = Calendar._SDN[(i + fdow) % 7];
        cell = cell.nextSibling;
    });
};

/** Função interna: esconde todas as caixas de combinação (combos) exibidas. */
Calendar.prototype._hideCombos = function () {
    this.monthsCombo.style.display = "none";
    this.yearsCombo.style.display = "none";
};

/** Função interna: inicia o arrasto do elemento. */
Calendar.prototype._dragStart = function (ev) {
    if (this.dragging) return;
    this.dragging = true;

    const { clientX, clientY } = ev;
    const posX = clientX + window.scrollX;
    const posY = clientY + window.scrollY;

    this.xOffs = posX - parseInt(this.element.style.left);
    this.yOffs = posY - parseInt(this.element.style.top);

    Calendar.addEvent(document, "mousemove", this._dragIt);
    Calendar.addEvent(document, "mouseup", this._dragEnd);
};

// BEGIN: DATE OBJECT PATCHES

/** Dias do mês padrão */
Date._MD = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

/** Constantes usadas para cálculos de tempo */
Date.SECOND = 1000; // milissegundos
Date.MINUTE = 60 * Date.SECOND;
Date.HOUR   = 60 * Date.MINUTE;
Date.DAY    = 24 * Date.HOUR;
Date.WEEK   = 7 * Date.DAY;

/**
 * Função para analisar a data em string conforme um formato fornecido
 */
Date.parseDate = function(str, fmt) {
  const today = new Date();
  let y = 0, m = -1, d = 0, hr = 0, min = 0;
  const a = str.split(/\W+/);
  const b = fmt.match(/%./g);

  a.forEach((part, i) => {
    if (!part) return;

    switch (b[i]) {
      case "%d":
      case "%e":
        d = parseInt(part, 10);
        break;
      case "%m":
        m = parseInt(part, 10) - 1;
        break;
      case "%Y":
      case "%y":
        y = parseInt(part, 10);
        if (y < 100) y += (y > 29) ? 1900 : 2000;
        break;
      case "%b":
      case "%B":
        m = Calendar._MN.findIndex(month => month.toLowerCase().startsWith(part.toLowerCase()));
        break;
      case "%H":
      case "%I":
      case "%k":
      case "%l":
        hr = parseInt(part, 10);
        break;
      case "%P":
      case "%p":
        hr = /pm/i.test(part) && hr < 12 ? hr + 12 : /am/i.test(part) && hr >= 12 ? hr - 12 : hr;
        break;
      case "%M":
        min = parseInt(part, 10);
        break;
    }
  });

  // Ajustes finais para valores não definidos
  y = isNaN(y) ? today.getFullYear() : y;
  m = isNaN(m) ? today.getMonth() : m;
  d = isNaN(d) ? today.getDate() : d;
  hr = isNaN(hr) ? today.getHours() : hr;
  min = isNaN(min) ? today.getMinutes() : min;

  return (y && m !== -1 && d) ? new Date(y, m, d, hr, min, 0) : today;
};

/**
 * Retorna o número de dias no mês atual ou no mês fornecido.
 */
Date.prototype.getMonthDays = function(month = this.getMonth()) {
  const year = this.getFullYear();
  const isLeapYear = ((year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0)) && month === 1);
  return isLeapYear ? 29 : Date._MD[month];
};

/**
 * Retorna o número do dia no ano.
 */
Date.prototype.getDayOfYear = function() {
  const startOfYear = new Date(this.getFullYear(), 0, 0);
  const diff = this - startOfYear;
  return Math.floor(diff / Date.DAY);
};

/**
 * Retorna o número da semana do ano (ISO 8601).
 */
Date.prototype.getWeekNumber = function() {
  const d = new Date(this);
  const DoW = d.getDay();
  
  // Ajuste para quinta-feira mais próxima (ISO 8601)
  d.setDate(d.getDate() - (DoW + 6) % 7 + 3);
  const ms = d.valueOf();
  
  // Define o início da primeira semana (4 de janeiro)
  d.setMonth(0);
  d.setDate(4);
  
  return Math.round((ms - d.valueOf()) / (7 * 864e5)) + 1;
};

// Verifica se as datas são iguais
Date.prototype.equalsTo = function(date) {
	return this.getFullYear() === date.getFullYear() &&
	  this.getMonth() === date.getMonth() &&
	  this.getDate() === date.getDate() &&
	  this.getHours() === date.getHours() &&
	  this.getMinutes() === date.getMinutes();
  };
  
  // Define apenas os campos de ano, mês e dia, mantendo a hora existente
  Date.prototype.setDateOnly = function(date) {
	const tmp = new Date(date);
	this.setFullYear(tmp.getFullYear());
	this.setMonth(tmp.getMonth());
	this.setDate(tmp.getDate());
  };
  
  // Formata a data de acordo com o formato fornecido
  Date.prototype.print = function(format) {
	const m = this.getMonth();
	const d = this.getDate();
	const y = this.getFullYear();
	const wn = this.getWeekNumber();
	const w = this.getDay();
	const hr = this.getHours();
	const min = this.getMinutes();
	const sec = this.getSeconds();
	const pm = hr >= 12;
	const ir = pm ? (hr - 12) : hr;
	const dy = this.getDayOfYear();
	const s = {
	  "%a": Calendar._SDN[w], // Nome do dia da semana abreviado
	  "%A": Calendar._DN[w], // Nome completo do dia da semana
	  "%b": Calendar._SMN[m], // Nome abreviado do mês
	  "%B": Calendar._MN[m], // Nome completo do mês
	  "%C": Math.floor(y / 100) + 1, // Século
	  "%d": String(d).padStart(2, '0'), // Dia do mês
	  "%e": d, // Dia do mês sem zero à esquerda
	  "%H": String(hr).padStart(2, '0'), // Hora (24h)
	  "%I": String(ir).padStart(2, '0'), // Hora (12h)
	  "%j": String(dy).padStart(3, '0'), // Dia do ano
	  "%k": hr, // Hora (24h, sem zero à esquerda)
	  "%l": ir, // Hora (12h, sem zero à esquerda)
	  "%m": String(m + 1).padStart(2, '0'), // Mês (01-12)
	  "%M": String(min).padStart(2, '0'), // Minuto
	  "%n": "\n", // Quebra de linha
	  "%p": pm ? "PM" : "AM", // AM/PM
	  "%P": pm ? "pm" : "am", // am/pm
	  "%s": Math.floor(this.getTime() / 1000), // Tempo em segundos
	  "%S": String(sec).padStart(2, '0'), // Segundo
	  "%t": "\t", // Tabulação
	  "%U": wn, // Número da semana (domingo como início)
	  "%W": wn, // Número da semana (segunda-feira como início)
	  "%V": wn, // Número ISO da semana
	  "%u": w + 1, // Dia da semana (1 = segunda)
	  "%w": w, // Dia da semana (0 = domingo)
	  "%y": String(y).slice(2), // Ano sem século
	  "%Y": y, // Ano completo
	  "%%": "%" // Literal de porcentagem
	};
  
	// Substitui as variáveis no formato fornecido
	return format.replace(/%./g, (match) => s[match] || match);
  };
  
  // Substitui o comportamento do setFullYear para ajustar o dia ao 28 quando o mês é alterado
  Date.prototype.setFullYear = function(y) {
	const tmp = new Date(this);
	tmp.setFullYear(y);
	if (tmp.getMonth() !== this.getMonth()) this.setDate(28); // Garantir que a data seja válida
	this.__msh_oldSetFullYear(y);
  };
  
  // Garantir que o comportamento original de setFullYear seja preservado
  Date.prototype.__msh_oldSetFullYear = Date.prototype.setFullYear;  

// END: DATE OBJECT PATCHES

// Global object to remember the calendar
window._dynarch_popupCalendar = null;

// Função comum para selecionar a data e preencher os campos
function selectDateField(cal, isEuropeFormat = false) {
  const { params } = cal;
  const update = cal.dateClicked || params.electric;

  const yearField = params.inputField.id;
  const dayField = isEuropeFormat ? `${params.baseField}_1` : `${params.baseField}_2`;
  const monthField = isEuropeFormat ? `${params.baseField}_2` : `${params.baseField}_1`;

  document.getElementById(monthField).value = cal.date.print('%m');
  document.getElementById(dayField).value = cal.date.print('%e');
  document.getElementById(yearField).value = cal.date.print('%Y');
}

// Função para selecionar data no formato europeu
function selectEuropeDate(cal) {
  selectDateField(cal, true);
}

// Função para selecionar data no formato padrão
function selectDate(cal) {
  selectDateField(cal, false);
}

// Define the full and short day and month names
const Calendar = {
	_DN: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
	_SDN: ["S", "M", "T", "W", "T", "F", "S", "S"],
  
	// First day of the week: "0" means Sunday, "1" means Monday, etc.
	_FD: 0,
  
	_MN: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
	_SMN: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
  
	// Tooltips
	_TT: {
	  INFO: "About the Calendar",
	  ABOUT: `
		DHTML Date/Time Selector
		(c) dynarch.com 2002-2005 / Author: Mihai Bazon
		For latest version visit: http://www.dynarch.com/projects/calendar/
		Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details.
  
		Date selection:
		- Use the \xab, \xbb buttons to select year
		- Use the ${String.fromCharCode(0x2039)}, ${String.fromCharCode(0x203a)} buttons to select month
		- Hold mouse button on any of the above buttons for faster selection.`,
	  ABOUT_TIME: `
		Time selection:
		- Click on any of the time parts to increase it
		- or Shift-click to decrease it
		- or click and drag for faster selection.`,
  
	  PREV_YEAR: "Prev. Year (Hold for Menu)",
	  PREV_MONTH: "Prev. Month (Hold for Menu)",
	  GO_TODAY: "Go to Today",
	  NEXT_MONTH: "Next Month (Hold for Menu)",
	  NEXT_YEAR: "Next Year (Hold for Menu)",
	  SEL_DATE: "Select Date",
	  DRAG_TO_MOVE: "Drag to Move",
	  PART_TODAY: " (Today)",
  
	  // Inform that "%s" represents the first day of the week, to be replaced with the day name
	  DAY_FIRST: "Display %ss first",
  
	  // Weekends are specified as a comma-separated list of numbers
	  WEEKEND: "0,6",
  
	  CLOSE: "Close Calendar",
	  TODAY: "Today",
	  TIME_PART: "(Shift-)Click or Drag to Change Value",
  
	  // Date formats
	  DEF_DATE_FORMAT: "%Y-%m-%d",
	  TT_DATE_FORMAT: "%b %e, %Y",
  
	  WK: "wk",
	  TIME: "Time:"
	}
  };
  
Calendar.setup = function (params) {
// Helper function to set default parameter values
function setDefault(paramName, defaultValue) {
	if (typeof params[paramName] === "undefined") {
	params[paramName] = defaultValue;
	}
}

// Set default values for various parameters
const defaults = {
	inputField: null,
	displayArea: null,
	button: null,
	eventName: "click",
	ifFormat: "%Y/%m/%d",
	daFormat: "%Y/%m/%d",
	singleClick: true,
	disableFunc: null,
	dateStatusFunc: params["disableFunc"], // Takes precedence if both are defined
	dateText: null,
	firstDay: null,
	align: "BR",
	range: [1900, 2999],
	weekNumbers: false,
	flat: null,
	flatCallback: null,
	onSelect: null,
	onClose: null,
	onUpdate: null,
	date: null,
	showsTime: false,
	timeFormat: "24",
	electric: true,
	step: 2,
	position: null,
	cache: false,
	showOthers: false,
	multiple: null
};

Object.keys(defaults).forEach(param => setDefault(param, defaults[param]));

// Process elements like inputField, displayArea, and button
const targetElements = ["inputField", "displayArea", "button"];
targetElements.forEach(element => {
	if (typeof params[element] === "string") {
	params[element] = document.getElementById(params[element]);
	}
});

if (!(params.flat || params.multiple || params.inputField || params.displayArea || params.button)) {
	alert("Calendar.setup:\n  Nothing to setup (no fields found). Please check your code.");
	return false;
}

// Function to handle date selection
const onSelect = (cal) => {
	const p = cal.params;
	const update = (cal.dateClicked || p.electric);
	
	if (update && p.inputField) {
	p.inputField.value = cal.date.print(p.ifFormat);
	if (typeof p.inputField.onchange === "function") p.inputField.onchange();
	}

	if (update && p.displayArea) p.displayArea.innerHTML = cal.date.print(p.daFormat);
	if (update && typeof p.onUpdate === "function") p.onUpdate(cal);
	if (update && p.flat && typeof p.flatCallback === "function") p.flatCallback(cal);
	if (update && p.singleClick && cal.dateClicked) cal.callCloseHandler();
};

// Handle flat calendar setup
if (params.flat !== null) {
	if (typeof params.flat === "string") params.flat = document.getElementById(params.flat);
	if (!params.flat) {
	alert("Calendar.setup:\n  Flat specified but can't find parent.");
	return false;
	}
	
	const cal = new Calendar(params.firstDay, params.date, params.onSelect || onSelect);
	cal.showsOtherMonths = params.showOthers;
	cal.showsTime = params.showsTime;
	cal.time24 = (params.timeFormat === "24");
	cal.params = params;
	cal.weekNumbers = params.weekNumbers;
	cal.setRange(params.range[0], params.range[1]);
	cal.setDateStatusHandler(params.dateStatusFunc);
	cal.getDateText = params.dateText;

	if (params.ifFormat) cal.setDateFormat(params.ifFormat);
	if (params.inputField && typeof params.inputField.value === "string") cal.parseDate(params.inputField.value);
	cal.create(params.flat);
	cal.show();
	return false;
}

// Trigger setup (when clicking on button, displayArea or inputField)
const triggerEl = params.button || params.displayArea || params.inputField;
triggerEl[`on${params.eventName}`] = function () {
	const dateEl = params.inputField || params.displayArea;
	const dateFmt = params.inputField ? params.ifFormat : params.daFormat;
	let mustCreate = false;
	let cal = window.calendar;

	if (dateEl) params.date = Date.parseDate(dateEl.value || dateEl.innerHTML, dateFmt);
	
	if (!(cal && params.cache)) {
	window.calendar = cal = new Calendar(params.firstDay, params.date, params.onSelect || onSelect, params.onClose || (cal => cal.hide()));
	cal.showsTime = params.showsTime;
	cal.time24 = (params.timeFormat === "24");
	cal.weekNumbers = params.weekNumbers;
	mustCreate = true;
	} else {
	if (params.date) cal.setDate(params.date);
	cal.hide();
	}

	// Handle multiple date selection
	if (params.multiple) {
	cal.multiple = {};
	params.multiple.forEach(d => {
		const ds = d.print("%Y%m%d");
		cal.multiple[ds] = d;
	});
	}

	cal.showsOtherMonths = params.showOthers;
	cal.yearStep = params.step;
	cal.setRange(params.range[0], params.range[1]);
	cal.params = params;
	cal.setDateStatusHandler(params.dateStatusFunc);
	cal.getDateText = params.dateText;
	cal.setDateFormat(dateFmt);

	if (mustCreate) cal.create();
	cal.refresh();

	if (!params.position) {
	cal.showAtElement(params.button || params.displayArea || params.inputField, params.align);
	} else {
	cal.showAt(params.position[0], params.position[1]);
	}

	return false;
};

return cal;
};