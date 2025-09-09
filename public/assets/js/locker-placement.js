// 락커 배치 관리 시스템
(function() {
    'use strict';

    // 전역 변수
    let selectedLocker = null;
    let selectedType = null;
    let lockers = [];
    let lockerTypes = [];
    let zones = [];
    let currentZone = null;
    let isDragging = false;
    let dragOffset = { x: 0, y: 0 };
    let zoomLevel = 1;

    // 초기화
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Locker Placement System Initialized');
        
        // 데이터 로드
        loadZones();
        loadLockerTypes();
        loadLockers();

        // 이벤트 리스너 설정
        setupEventListeners();
    });

    // 이벤트 리스너 설정
    function setupEventListeners() {
        const canvas = document.getElementById('lockerCanvas');
        
        // 캔버스 이벤트
        canvas.addEventListener('mousedown', handleMouseDown);
        canvas.addEventListener('mousemove', handleMouseMove);
        canvas.addEventListener('mouseup', handleMouseUp);
        canvas.addEventListener('click', handleCanvasClick);
        canvas.addEventListener('contextmenu', handleRightClick);
        
        // 키보드 이벤트
        document.addEventListener('keydown', handleKeyDown);
    }

    // 구역 로드
    function loadZones() {
        // API 호출 또는 더미 데이터
        zones = [
            { id: 1, name: 'A구역' },
            { id: 2, name: 'B구역' },
            { id: 3, name: 'C구역' }
        ];
        
        renderZoneTabs();
        
        // 첫 번째 구역 선택
        if (zones.length > 0) {
            selectZone(zones[0]);
        }
    }

    // 구역 탭 렌더링
    function renderZoneTabs() {
        const zoneTabs = document.getElementById('zoneTabs');
        zoneTabs.innerHTML = '';
        
        zones.forEach(zone => {
            const li = document.createElement('li');
            li.className = 'nav-item';
            
            const a = document.createElement('a');
            a.className = 'nav-link';
            a.href = '#';
            a.textContent = zone.name;
            a.onclick = (e) => {
                e.preventDefault();
                selectZone(zone);
            };
            
            li.appendChild(a);
            zoneTabs.appendChild(li);
        });
    }

    // 구역 선택
    function selectZone(zone) {
        currentZone = zone;
        
        // 탭 활성화 상태 업데이트
        document.querySelectorAll('#zoneTabs .nav-link').forEach((tab, index) => {
            if (zones[index].id === zone.id) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
        
        // 해당 구역의 락커 로드
        loadLockers();
    }

    // 락커 타입 로드
    function loadLockerTypes() {
        // API 호출 또는 더미 데이터
        lockerTypes = [
            { id: 1, name: '일반락커', width: 30, depth: 30, height: 30, color: '#4A90E2' },
            { id: 2, name: '장락커', width: 30, depth: 30, height: 90, color: '#BD10E0' },
            { id: 3, name: '대형락커', width: 60, depth: 40, height: 180, color: '#F5A623' }
        ];
        
        renderLockerTypes();
    }

    // 락커 타입 목록 렌더링
    function renderLockerTypes() {
        const lockerTypeList = document.getElementById('lockerTypeList');
        lockerTypeList.innerHTML = '';
        
        lockerTypes.forEach(type => {
            const div = document.createElement('div');
            div.className = 'locker-type-item';
            div.style.cssText = `
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s;
            `;
            
            div.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <div style="width: 60px; height: 60px; margin-right: 10px;">
                        <svg width="60" height="60" viewBox="0 0 60 60">
                            <rect x="5" y="5" width="50" height="50" 
                                  fill="${type.color}20" 
                                  stroke="${type.color}" 
                                  stroke-width="2" 
                                  rx="5" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-weight: bold;">${type.name}</div>
                        <div style="font-size: 12px; color: #666;">
                            ${type.width}x${type.depth}x${type.height}cm
                        </div>
                    </div>
                </div>
            `;
            
            // 클릭 이벤트
            div.onclick = () => selectLockerType(type);
            
            // 더블클릭으로 락커 추가
            div.ondblclick = () => addLockerToCanvas(type);
            
            // 호버 효과
            div.onmouseover = () => {
                div.style.backgroundColor = '#f0f0f0';
                div.style.borderColor = type.color;
            };
            div.onmouseout = () => {
                if (selectedType !== type) {
                    div.style.backgroundColor = '';
                    div.style.borderColor = '#ddd';
                }
            };
            
            lockerTypeList.appendChild(div);
        });
    }

    // 락커 타입 선택
    function selectLockerType(type) {
        selectedType = type;
        
        // 선택 상태 업데이트
        document.querySelectorAll('.locker-type-item').forEach((item, index) => {
            if (lockerTypes[index] === type) {
                item.style.backgroundColor = '#e8f4ff';
                item.style.borderColor = type.color;
            } else {
                item.style.backgroundColor = '';
                item.style.borderColor = '#ddd';
            }
        });
    }

    // 락커 로드
    function loadLockers() {
        // API 호출 또는 더미 데이터
        lockers = [
            { id: 1, typeId: 1, x: 100, y: 100, rotation: 0 },
            { id: 2, typeId: 2, x: 200, y: 100, rotation: 0 },
            { id: 3, typeId: 1, x: 300, y: 100, rotation: 90 }
        ];
        
        renderLockers();
    }

    // 락커 렌더링
    function renderLockers() {
        const lockersGroup = document.getElementById('lockersGroup');
        lockersGroup.innerHTML = '';
        
        lockers.forEach(locker => {
            const type = lockerTypes.find(t => t.id === locker.typeId);
            if (!type) return;
            
            const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
            g.setAttribute('transform', `translate(${locker.x}, ${locker.y}) rotate(${locker.rotation})`);
            g.setAttribute('data-locker-id', locker.id);
            g.style.cursor = 'move';
            
            // 락커 사각형
            const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            rect.setAttribute('x', -type.width / 2);
            rect.setAttribute('y', -type.depth / 2);
            rect.setAttribute('width', type.width);
            rect.setAttribute('height', type.depth);
            rect.setAttribute('fill', type.color + '40');
            rect.setAttribute('stroke', type.color);
            rect.setAttribute('stroke-width', '2');
            rect.setAttribute('rx', '5');
            
            // 전면 표시선
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', -type.width / 2 + 5);
            line.setAttribute('y1', type.depth / 2 - 5);
            line.setAttribute('x2', type.width / 2 - 5);
            line.setAttribute('y2', type.depth / 2 - 5);
            line.setAttribute('stroke', type.color);
            line.setAttribute('stroke-width', '3');
            line.setAttribute('opacity', '0.8');
            
            // 락커 번호
            const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            text.setAttribute('x', '0');
            text.setAttribute('y', '0');
            text.setAttribute('text-anchor', 'middle');
            text.setAttribute('dominant-baseline', 'middle');
            text.setAttribute('fill', '#333');
            text.setAttribute('font-size', '14');
            text.setAttribute('font-weight', 'bold');
            text.textContent = locker.id;
            
            g.appendChild(rect);
            g.appendChild(line);
            g.appendChild(text);
            
            // 클릭 이벤트
            g.addEventListener('click', (e) => {
                e.stopPropagation();
                selectLocker(locker);
            });
            
            lockersGroup.appendChild(g);
        });
    }

    // 락커 선택
    function selectLocker(locker) {
        selectedLocker = locker;
        
        // 선택 상태 표시
        document.querySelectorAll('#lockersGroup g').forEach(g => {
            const lockerId = parseInt(g.getAttribute('data-locker-id'));
            const rect = g.querySelector('rect');
            
            if (lockerId === locker.id) {
                rect.setAttribute('stroke-width', '3');
                rect.setAttribute('stroke-dasharray', '5,5');
            } else {
                rect.setAttribute('stroke-width', '2');
                rect.removeAttribute('stroke-dasharray');
            }
        });
    }

    // 캔버스에 락커 추가
    function addLockerToCanvas(type) {
        if (!type || !currentZone) return;
        
        const newLocker = {
            id: lockers.length + 1,
            typeId: type.id,
            x: 150,
            y: 150,
            rotation: 0
        };
        
        lockers.push(newLocker);
        renderLockers();
        selectLocker(newLocker);
    }

    // 캔버스 클릭 핸들러
    function handleCanvasClick(e) {
        const canvas = document.getElementById('lockerCanvas');
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // 빈 공간 클릭시 선택 해제
        if (e.target === canvas || e.target.id === 'grid') {
            selectedLocker = null;
            renderLockers();
        }
    }

    // 마우스 다운 핸들러
    function handleMouseDown(e) {
        if (!selectedLocker) return;
        
        isDragging = true;
        const canvas = document.getElementById('lockerCanvas');
        const rect = canvas.getBoundingClientRect();
        
        dragOffset.x = e.clientX - rect.left - selectedLocker.x;
        dragOffset.y = e.clientY - rect.top - selectedLocker.y;
    }

    // 마우스 이동 핸들러
    function handleMouseMove(e) {
        if (!isDragging || !selectedLocker) return;
        
        const canvas = document.getElementById('lockerCanvas');
        const rect = canvas.getBoundingClientRect();
        
        selectedLocker.x = e.clientX - rect.left - dragOffset.x;
        selectedLocker.y = e.clientY - rect.top - dragOffset.y;
        
        renderLockers();
    }

    // 마우스 업 핸들러
    function handleMouseUp() {
        isDragging = false;
    }

    // 우클릭 핸들러
    function handleRightClick(e) {
        e.preventDefault();
        // 컨텍스트 메뉴 표시
    }

    // 키보드 핸들러
    function handleKeyDown(e) {
        if (!selectedLocker) return;
        
        switch(e.key) {
            case 'Delete':
                deleteSelected();
                break;
            case 'r':
            case 'R':
                rotateSelected();
                break;
        }
    }

    // 줌 인
    window.zoomIn = function() {
        zoomLevel = Math.min(zoomLevel * 1.2, 3);
        applyZoom();
    };

    // 줌 아웃
    window.zoomOut = function() {
        zoomLevel = Math.max(zoomLevel / 1.2, 0.5);
        applyZoom();
    };

    // 줌 리셋
    window.resetZoom = function() {
        zoomLevel = 1;
        applyZoom();
    };

    // 줌 적용
    function applyZoom() {
        const lockersGroup = document.getElementById('lockersGroup');
        lockersGroup.setAttribute('transform', `scale(${zoomLevel})`);
    }

    // 선택된 락커 삭제
    window.deleteSelected = function() {
        if (!selectedLocker) return;
        
        const index = lockers.indexOf(selectedLocker);
        if (index > -1) {
            lockers.splice(index, 1);
            selectedLocker = null;
            renderLockers();
        }
    };

    // 선택된 락커 회전
    window.rotateSelected = function() {
        if (!selectedLocker) return;
        
        selectedLocker.rotation = (selectedLocker.rotation + 90) % 360;
        renderLockers();
    };

    // 레이아웃 저장
    window.saveLayout = function() {
        console.log('Saving layout...', lockers);
        
        // API 호출
        fetch(window.LockerConfig.apiUrl + '/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                [window.LockerConfig.csrfHeader]: window.LockerConfig.csrfHash
            },
            body: JSON.stringify({
                zone: currentZone,
                lockers: lockers
            })
        })
        .then(response => response.json())
        .then(data => {
            alert('저장되었습니다.');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('저장 중 오류가 발생했습니다.');
        });
    };

    // 락커 등록 모달 열기
    window.openLockerRegistrationModal = function() {
        $('#lockerRegistrationModal').modal('show');
    };

    // 락커 등록
    window.registerLocker = function() {
        const name = document.getElementById('lockerName').value;
        const width = parseInt(document.getElementById('lockerWidth').value);
        const depth = parseInt(document.getElementById('lockerDepth').value);
        const height = parseInt(document.getElementById('lockerHeight').value);
        const color = document.getElementById('lockerColor').value;
        
        if (!name || !width || !depth || !height) {
            alert('모든 필드를 입력해주세요.');
            return;
        }
        
        const newType = {
            id: lockerTypes.length + 1,
            name: name,
            width: width,
            depth: depth,
            height: height,
            color: color
        };
        
        lockerTypes.push(newType);
        renderLockerTypes();
        
        // 모달 닫기
        $('#lockerRegistrationModal').modal('hide');
        
        // 폼 리셋
        document.getElementById('lockerRegistrationForm').reset();
    };

})();