// 락커 관리 JavaScript

class LockerManager {
    constructor() {
        this.currentCanvas = null;
        this.currentMode = 'none'; // none, zone, group, number
        this.selectedZone = null;
        this.selectedGroup = null;
        this.numberingStart = 1;
        
        this.initializeEvents();
    }

    initializeEvents() {
        // 도면 캔버스 초기화
        $('.drawing-board').each((index, element) => {
            const canvas = new fabric.Canvas(element);
            $(element).data('canvas', canvas);
            
            // 이미지 로드
            const img = $(element).find('img')[0];
            fabric.Image.fromURL(img.src, (oImg) => {
                canvas.setBackgroundImage(oImg, canvas.renderAll.bind(canvas), {
                    scaleX: canvas.width / oImg.width,
                    scaleY: canvas.height / oImg.height
                });
            });
        });

        // 구역 추가 버튼
        $('.add-zone').on('click', (e) => {
            const floor_sno = $(e.target).data('floor');
            const canvas = $(`.drawing-board[data-floor="${floor_sno}"]`).data('canvas');
            this.currentCanvas = canvas;
            this.currentMode = 'zone';
            this.enableDrawing();
        });

        // 락커 그룹 추가 버튼
        $(document).on('click', '.add-group', (e) => {
            const zone_sno = $(e.target).data('zone');
            this.selectedZone = zone_sno;
            this.currentMode = 'group';
            this.enableDrawing();
        });

        // 정면도 설정 버튼
        $(document).on('click', '.set-front', (e) => {
            const group_sno = $(e.target).data('group');
            this.selectedGroup = group_sno;
            this.showFrontModal();
        });

        // 번호 지정 모드 버튼
        $(document).on('click', '.set-numbers', (e) => {
            const front_sno = $(e.target).data('front');
            this.currentMode = 'number';
            this.enableNumbering(front_sno);
        });
    }

    enableDrawing() {
        if (!this.currentCanvas) return;

        let isDrawing = false;
        let startPoint;
        
        this.currentCanvas.on('mouse:down', (o) => {
            isDrawing = true;
            const pointer = this.currentCanvas.getPointer(o.e);
            startPoint = { x: pointer.x, y: pointer.y };
            
            const rect = new fabric.Rect({
                left: startPoint.x,
                top: startPoint.y,
                width: 0,
                height: 0,
                fill: this.currentMode === 'zone' ? 'rgba(0,0,255,0.3)' : 'rgba(255,0,0,0.3)',
                stroke: this.currentMode === 'zone' ? 'blue' : 'red',
                strokeWidth: 2
            });
            
            this.currentCanvas.add(rect);
            this.currentCanvas.setActiveObject(rect);
        });

        this.currentCanvas.on('mouse:move', (o) => {
            if (!isDrawing) return;
            
            const pointer = this.currentCanvas.getPointer(o.e);
            const activeObject = this.currentCanvas.getActiveObject();
            
            if (activeObject) {
                activeObject.set({
                    width: Math.abs(pointer.x - startPoint.x),
                    height: Math.abs(pointer.y - startPoint.y)
                });
                
                if (pointer.x < startPoint.x) {
                    activeObject.set({ left: pointer.x });
                }
                if (pointer.y < startPoint.y) {
                    activeObject.set({ top: pointer.y });
                }
                
                this.currentCanvas.renderAll();
            }
        });

        this.currentCanvas.on('mouse:up', () => {
            isDrawing = false;
            const activeObject = this.currentCanvas.getActiveObject();
            if (activeObject) {
                const coords = {
                    left: activeObject.left,
                    top: activeObject.top,
                    width: activeObject.width,
                    height: activeObject.height
                };

                if (this.currentMode === 'zone') {
                    $('#zone_coords').val(JSON.stringify(coords));
                    $('#zoneModal').modal('show');
                } else if (this.currentMode === 'group') {
                    $('#group_coords').val(JSON.stringify(coords));
                    $('#group_zone_sno').val(this.selectedZone);
                    $('#groupModal').modal('show');
                }
            }
        });
    }

    showFrontModal() {
        $('#front_group_sno').val(this.selectedGroup);
        $('#frontModal').modal('show');
    }

    enableNumbering(front_sno) {
        // 락커 번호 지정 모드
        const $lockers = $(`.locker[data-front="${front_sno}"]`);
        let isSelecting = false;
        let startLocker = null;

        $lockers.on('mousedown', (e) => {
            isSelecting = true;
            startLocker = $(e.target);
            this.numberingStart = parseInt(prompt('시작 번호를 입력하세요:', '1')) || 1;
        });

        $lockers.on('mouseover', (e) => {
            if (!isSelecting) return;
            
            const currentLocker = $(e.target);
            const startIndex = $lockers.index(startLocker);
            const currentIndex = $lockers.index(currentLocker);
            
            $lockers.removeClass('selected');
            
            const start = Math.min(startIndex, currentIndex);
            const end = Math.max(startIndex, currentIndex);
            
            for (let i = start; i <= end; i++) {
                $lockers.eq(i).addClass('selected');
            }
        });

        $lockers.on('mouseup', () => {
            isSelecting = false;
            const $selected = $('.locker.selected');
            
            let number = this.numberingStart;
            const numbers = {};
            
            $selected.each((index, element) => {
                const $locker = $(element);
                const locker_sno = $locker.data('sno');
                numbers[locker_sno] = number++;
                $locker.text(numbers[locker_sno]);
            });

            // 번호 저장
            $.ajax({
                url: '/locker/update_locker_numbers',
                type: 'POST',
                data: {
                    front_sno: front_sno,
                    numbers: JSON.stringify(numbers)
                },
                success: (response) => {
                    if (response.status === 'success') {
                        alert('락커 번호가 저장되었습니다.');
                    } else {
                        alert('락커 번호 저장에 실패했습니다.');
                    }
                }
            });
        });
    }
}

// 페이지 로드 시 초기화
$(document).ready(() => {
    window.lockerManager = new LockerManager();
}); 