/**
 * STORY 유동형 그룹 스크롤 모션
 *
 * 기능:
 * - 글씨와 이미지 개수 자동 인식
 * - 이미지 원본 비율 유지
 * - 글씨별 images-숫자로 담당 이미지 수 지정
 * - 담당한 마지막 이미지가 중간쯤 왔을 때 글씨 전환
 * - 이전 글씨가 완전히 사라진 후 다음 글씨 등장
 * - 이미지 개수에 따라 스크롤 높이 자동 계산
 * - 이미지 좌우 자동 교차 배치
 *
 * 기존 SANC 모션 스니펫은 수정하지 않습니다.
 */
function seanhaha_story_flexible_group_motion() {

    /* ==================================================
       CSS
    ================================================== */

    $story_css = <<<'CSS'

/* ==================================================
   전체 스크롤 구간
================================================== */

body .story-section {
    position: relative !important;
    display: block !important;

    width: 100% !important;

    /*
     * 실제 높이는 이미지 개수에 맞춰
     * JavaScript가 자동으로 변경합니다.
     */
    height: var(
        --story-scroll-height,
        500vh
    ) !important;

    min-height: var(
        --story-scroll-height,
        500vh
    ) !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow: visible !important;

    background: #ffffff;

    isolation: isolate;
}


/* Elementor 내부 래퍼 */
body .story-section > .e-con-inner {
    position: relative !important;
    display: block !important;

    width: 100% !important;
    height: 100% !important;
    min-height: 100% !important;

    max-width: none !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow: visible !important;
}


/*
 * sticky를 방해하는 상위 컨테이너에
 * JavaScript가 자동으로 추가하는 클래스입니다.
 */
body .story-sticky-parent {
    overflow: visible !important;
    overflow-x: visible !important;
    overflow-y: visible !important;
}


/* ==================================================
   화면에 고정되는 무대
================================================== */

body .story-section > .story-stage,
body .story-section > .e-con-inner > .story-stage {
    position: sticky !important;

    top: 0 !important;

    display: block !important;

    width: 100% !important;
    height: 100vh !important;
    min-height: 100vh !important;

    max-width: none !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow: hidden !important;

    background: #ffffff;

    z-index: 1 !important;

    isolation: isolate;
}


/* story-stage 내부 Elementor 래퍼 */
body .story-stage > .e-con-inner {
    position: relative !important;
    display: block !important;

    width: 100% !important;
    height: 100% !important;

    max-width: none !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow: hidden !important;
}


/* ==================================================
   글씨 공통
================================================== */

body .story-stage .story-text {
    position: absolute !important;

    left: 50% !important;
    top: 50% !important;

    right: auto !important;
    bottom: auto !important;

    width: min(
        42vw,
        680px
    ) !important;

    max-width: 680px !important;

    margin: 0 !important;
    padding: 0 20px !important;

    opacity: 0;
    visibility: hidden;

    text-align: center !important;

    z-index: 100 !important;

    pointer-events: none !important;

    will-change: transform, opacity;

    animation: none !important;
    transition: none !important;
}


/* Elementor 글씨 내부 */
body .story-stage .story-text
.elementor-widget-container {
    display: block !important;

    width: 100% !important;

    margin: 0 auto !important;
    padding: 0 !important;

    opacity: 1 !important;
    visibility: visible !important;

    text-align: center !important;

    animation: none !important;
    transition: none !important;
}


/* 제목과 문단 */
body .story-stage .story-text h1,
body .story-stage .story-text h2,
body .story-stage .story-text h3,
body .story-stage .story-text h4,
body .story-stage .story-text h5,
body .story-stage .story-text h6,
body .story-stage .story-text p,
body .story-stage .story-text
.elementor-heading-title {
    margin: 0 !important;
    padding: 0 !important;

    opacity: 1 !important;
    visibility: visible !important;

    text-align: center !important;

    animation: none !important;
    transition: none !important;
}


/* Elementor 기본 등장 애니메이션 제거 */
body .story-stage
.story-text.elementor-invisible,

body .story-stage
.story-text .elementor-invisible {
    visibility: visible !important;
}


/* ==================================================
   이미지 공통
================================================== */

body .story-stage .story-image {
    position: absolute !important;

    top: 50% !important;

    /*
     * 이미지의 가로 최대 크기만 제한합니다.
     * 높이는 원본 비율에 따라 자동으로 결정됩니다.
     */
    width: min(
        28vw,
        450px
    ) !important;

    max-width: 450px !important;

    height: auto !important;
    max-height: none !important;

    margin: 0 !important;
    padding: 0 !important;

    opacity: 1 !important;
    visibility: visible !important;

    z-index: 5 !important;

    will-change: transform;

    backface-visibility: hidden;

    animation: none !important;
    transition: none !important;
}


/* Elementor 이미지 내부 상자 */
body .story-stage .story-image
.elementor-widget-container {
    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    width: 100% !important;
    height: auto !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow: visible !important;

    border-radius: 20px;
}


/*
 * 이미지 원본 비율 유지
 * 이미지가 잘리지 않습니다.
 */
body .story-stage .story-image img {
    display: block !important;

    width: auto !important;
    height: auto !important;

    max-width: 100% !important;
    max-height: 68vh !important;

    margin: 0 auto !important;
    padding: 0 !important;

    object-fit: contain !important;
    object-position: center center !important;

    border-radius: 20px;

    backface-visibility: hidden;
}


/* ==================================================
   이미지 좌우 자동 배치
================================================== */

body .story-stage
.story-image.story-image-right {
    left: auto !important;
    right: 1.5vw !important;
}


body .story-stage
.story-image.story-image-left {
    left: 1.5vw !important;
    right: auto !important;
}


/* 일부 이미지의 위치를 조금 다르게 배치 */
body .story-stage
.story-image.story-image-right.story-image-offset {
    right: 3vw !important;
}


body .story-stage
.story-image.story-image-left.story-image-offset {
    left: 3vw !important;
}


/* ==================================================
   태블릿
================================================== */

@media screen and (max-width: 1024px) {

    body .story-stage .story-text {
        width: min(
            48vw,
            540px
        ) !important;
    }

    body .story-stage .story-image {
        width: min(
            33vw,
            370px
        ) !important;

        max-width: 370px !important;
    }

    body .story-stage .story-image img {
        max-height: 62vh !important;
    }

    body .story-stage
    .story-image.story-image-right,
    body .story-stage
    .story-image.story-image-right.story-image-offset {
        right: 1vw !important;
    }

    body .story-stage
    .story-image.story-image-left,
    body .story-stage
    .story-image.story-image-left.story-image-offset {
        left: 1vw !important;
    }

}


/* ==================================================
   모바일
================================================== */

@media screen and (max-width: 767px) {

    body .story-stage .story-text {
        width: calc(
            100vw - 50px
        ) !important;

        max-width: 430px !important;

        padding: 0 10px !important;
    }

    body .story-stage .story-image {
        width: 52vw !important;
        max-width: 290px !important;
    }

    body .story-stage .story-image img {
        max-height: 52vh !important;
    }

    body .story-stage
    .story-image.story-image-right,
    body .story-stage
    .story-image.story-image-right.story-image-offset {
        right: -7vw !important;
    }

    body .story-stage
    .story-image.story-image-left,
    body .story-stage
    .story-image.story-image-left.story-image-offset {
        left: -7vw !important;
    }

}

CSS;


    wp_register_style(
        'seanhaha-story-flexible-group-style',
        false,
        array(),
        '15.0.0'
    );


    wp_enqueue_style(
        'seanhaha-story-flexible-group-style'
    );


    wp_add_inline_style(
        'seanhaha-story-flexible-group-style',
        $story_css
    );


    /* ==================================================
       JavaScript
    ================================================== */

    $story_js = <<<'JS'

(function () {

    function initStoryFlexibleGroupMotion() {

        /*
         * 중복 실행 방지
         */
        if (
            window
                .seanhahaStoryFlexibleGroupStarted
        ) {
            return;
        }


        /*
         * GSAP 확인
         */
        if (
            typeof gsap ===
            'undefined'
        ) {

            console.warn(
                '스토리 모션: GSAP을 찾지 못했습니다.'
            );

            return;

        }


        const sections =
            Array.from(
                document.querySelectorAll(
                    '.story-section'
                )
            );


        if (!sections.length) {
            return;
        }


        window
            .seanhahaStoryFlexibleGroupStarted =
            true;


        /* ==================================================
           계산 함수
        ================================================== */

        function clamp(
            value,
            min,
            max
        ) {

            return Math.min(
                Math.max(
                    value,
                    min
                ),
                max
            );

        }


        function range(
            value,
            start,
            end
        ) {

            if (start === end) {
                return 0;
            }


            return clamp(
                (
                    value -
                    start
                ) /
                (
                    end -
                    start
                ),
                0,
                1
            );

        }


        /*
         * 부드러운 가속·감속 계산
         */
        function smootherStep(value) {

            value =
                clamp(
                    value,
                    0,
                    1
                );


            return value *
                value *
                value *
                (
                    value *
                    (
                        value * 6 -
                        15
                    ) +
                    10
                );

        }


        function lerp(
            start,
            end,
            progress
        ) {

            return start +
                (
                    end -
                    start
                ) *
                progress;

        }


        /*
         * story-text 클래스 중에서
         * images-숫자를 찾아 숫자만 반환합니다.
         */
        function getRequestedImageCount(
            text
        ) {

            const classNames =
                Array.from(
                    text.classList
                );


            for (
                let index = 0;
                index <
                classNames.length;
                index++
            ) {

                const match =
                    classNames[index]
                        .match(
                            /^images-(\d+)$/
                        );


                if (match) {

                    return Math.max(
                        parseInt(
                            match[1],
                            10
                        ),
                        0
                    );

                }

            }


            return null;

        }


        /* ==================================================
           각 story-section 준비
        ================================================== */

        const states =
            [];


        sections.forEach(
            function (section) {

                const stage =
                    section.querySelector(
                        '.story-stage'
                    );


                const texts =
                    Array.from(
                        stage
                            ? stage
                                .querySelectorAll(
                                    '.story-text'
                                )
                            : []
                    );


                const images =
                    Array.from(
                        stage
                            ? stage
                                .querySelectorAll(
                                    '.story-image'
                                )
                            : []
                    );


                if (
                    !stage ||
                    !texts.length ||
                    !images.length
                ) {

                    console.warn(
                        '스토리 모션: story-stage, story-text 또는 story-image를 찾지 못했습니다.',
                        section
                    );

                    return;

                }


                /* ==================================================
                   이미지 개수에 따른 전체 높이
                ================================================== */

                /*
                 * 숫자가 클수록 이미지 움직임이 느려지고
                 * 전체 스크롤 구간이 길어집니다.
                 */
                const desktopPerImage =
                    64;


                const tabletPerImage =
                    59;


                const mobilePerImage =
                    54;


                const desktopHeight =
                    Math.max(
                        320,
                        175 +
                        images.length *
                        desktopPerImage
                    );


                const tabletHeight =
                    Math.max(
                        300,
                        165 +
                        images.length *
                        tabletPerImage
                    );


                const mobileHeight =
                    Math.max(
                        280,
                        155 +
                        images.length *
                        mobilePerImage
                    );


                function applySectionHeight() {

                    let sectionHeight =
                        desktopHeight;


                    if (
                        window.innerWidth <=
                        767
                    ) {

                        sectionHeight =
                            mobileHeight;

                    } else if (
                        window.innerWidth <=
                        1024
                    ) {

                        sectionHeight =
                            tabletHeight;

                    }


                    section.style
                        .setProperty(
                            '--story-scroll-height',
                            sectionHeight +
                            'vh'
                        );

                }


                applySectionHeight();


                /* ==================================================
                   이미지 좌우 자동 배치
                ================================================== */

                images.forEach(
                    function (
                        image,
                        index
                    ) {

                        image.classList
                            .remove(
                                'story-image-left',
                                'story-image-right',
                                'story-image-offset'
                            );


                        /*
                         * 첫 번째는 오른쪽,
                         * 두 번째는 왼쪽으로 반복됩니다.
                         */
                        if (
                            index % 2 === 0
                        ) {

                            image.classList
                                .add(
                                    'story-image-right'
                                );

                        } else {

                            image.classList
                                .add(
                                    'story-image-left'
                                );

                        }


                        /*
                         * 일부 이미지의 가로 위치를
                         * 조금씩 다르게 배치합니다.
                         */
                        if (
                            index % 4 === 2 ||
                            index % 4 === 3
                        ) {

                            image.classList
                                .add(
                                    'story-image-offset'
                                );

                        }

                    }
                );


                /* ==================================================
                   글씨별 담당 이미지 수 계산
                ================================================== */

                const imageCount =
                    images.length;


                const requestedCounts =
                    texts.map(
                        function (text) {

                            return getRequestedImageCount(
                                text
                            );

                        }
                    );


                const unspecifiedIndexes =
                    [];


                let requestedTotal =
                    0;


                requestedCounts.forEach(
                    function (
                        count,
                        index
                    ) {

                        if (
                            count === null
                        ) {

                            unspecifiedIndexes
                                .push(
                                    index
                                );

                        } else {

                            requestedTotal +=
                                count;

                        }

                    }
                );


                /*
                 * 사용자가 지정한 값으로 시작합니다.
                 */
                const groupCounts =
                    requestedCounts.map(
                        function (count) {

                            return count ===
                                null
                                ? 0
                                : count;

                        }
                    );


                /*
                 * 아직 배정되지 않은 이미지 개수
                 */
                let remainingImages =
                    Math.max(
                        imageCount -
                        requestedTotal,
                        0
                    );


                /*
                 * images-숫자가 없는 글씨에는
                 * 남은 이미지를 균등 분배합니다.
                 */
                if (
                    unspecifiedIndexes.length
                ) {

                    unspecifiedIndexes
                        .forEach(
                            function (
                                textIndex,
                                order
                            ) {

                                const remainingTexts =
                                    unspecifiedIndexes
                                        .length -
                                    order;


                                const share =
                                    Math.ceil(
                                        remainingImages /
                                        remainingTexts
                                    );


                                groupCounts[
                                    textIndex
                                ] =
                                    share;


                                remainingImages -=
                                    share;

                            }
                        );

                } else if (
                    remainingImages > 0
                ) {

                    /*
                     * 모든 글씨에 images-숫자가 있고
                     * 이미지가 남으면 마지막 글씨에 포함합니다.
                     */
                    groupCounts[
                        groupCounts.length -
                        1
                    ] +=
                        remainingImages;

                }


                /*
                 * 지정 합계가 실제 이미지보다 많으면
                 * 존재하는 이미지까지만 사용합니다.
                 */
                let availableImages =
                    imageCount;


                for (
                    let index = 0;
                    index <
                    groupCounts.length;
                    index++
                ) {

                    const usableCount =
                        Math.min(
                            groupCounts[index],
                            availableImages
                        );


                    groupCounts[index] =
                        usableCount;


                    availableImages -=
                        usableCount;

                }


                /*
                 * 모든 값이 0인 특수 상황에는
                 * 이미지를 글씨마다 균등 분배합니다.
                 */
                const finalAssignedTotal =
                    groupCounts.reduce(
                        function (
                            total,
                            count
                        ) {

                            return total +
                                count;

                        },
                        0
                    );


                if (
                    finalAssignedTotal === 0
                ) {

                    let remaining =
                        imageCount;


                    groupCounts
                        .forEach(
                            function (
                                count,
                                index
                            ) {

                                const remainingTexts =
                                    groupCounts
                                        .length -
                                    index;


                                const share =
                                    Math.ceil(
                                        remaining /
                                        remainingTexts
                                    );


                                groupCounts[index] =
                                    share;


                                remaining -=
                                    share;

                            }
                        );

                }


                /* ==================================================
                   글씨별 담당 이미지 시작·종료 번호
                ================================================== */

                const textGroups =
                    [];


                let imageCursor =
                    0;


                groupCounts.forEach(
                    function (
                        count,
                        textIndex
                    ) {

                        const startIndex =
                            imageCursor;


                        const endIndex =
                            Math.min(
                                startIndex +
                                count,
                                imageCount
                            );


                        textGroups.push({

                            textIndex:
                                textIndex,

                            startIndex:
                                startIndex,

                            endIndex:
                                endIndex

                        });


                        imageCursor =
                            endIndex;

                    }
                );


                /* ==================================================
                   sticky 방해 상위 요소 보정
                ================================================== */

                let parent =
                    section.parentElement;


                while (
                    parent &&
                    parent !==
                    document.body
                ) {

                    const style =
                        window
                            .getComputedStyle(
                                parent
                            );


                    if (
                        style.overflow ===
                            'hidden' ||
                        style.overflow ===
                            'clip' ||
                        style.overflowY ===
                            'hidden' ||
                        style.overflowY ===
                            'clip'
                    ) {

                        parent.classList
                            .add(
                                'story-sticky-parent'
                            );

                    }


                    parent =
                        parent.parentElement;

                }


                /* ==================================================
                   Elementor 기본 애니메이션 제거
                ================================================== */

                texts.forEach(
                    function (text) {

                        text.classList
                            .remove(
                                'elementor-invisible',
                                'animated',
                                'fadeIn',
                                'fadeInUp',
                                'fadeInDown',
                                'fadeInLeft',
                                'fadeInRight',
                                'zoomIn'
                            );

                    }
                );


                /*
                 * 글씨 초기 상태
                 */
                gsap.set(
                    texts,
                    {
                        xPercent:
                            -50,

                        yPercent:
                            -50,

                        x:
                            0,

                        y:
                            28,

                        opacity:
                            0,

                        visibility:
                            'hidden',

                        force3D:
                            true
                    }
                );


                /*
                 * 이미지 중심 기준
                 */
                gsap.set(
                    images,
                    {
                        yPercent:
                            -50,

                        force3D:
                            true
                    }
                );


                /* ==================================================
                   글씨 opacity setter

                   투명도에 quickTo를 사용하지 않습니다.
                   현재 스크롤 위치의 값을 즉시 적용해서
                   이전 글씨의 잔상이 남지 않도록 합니다.
                ================================================== */

                const textOpacityTo =
                    texts.map(
                        function (text) {

                            return gsap
                                .quickSetter(
                                    text,
                                    'opacity'
                                );

                        }
                    );


                /*
                 * 글씨의 세로 이동은 부드럽게 유지합니다.
                 */
                const textYTo =
                    texts.map(
                        function (text) {

                            return gsap
                                .quickTo(
                                    text,
                                    'y',
                                    {
                                        duration:
                                            0.32,

                                        ease:
                                            'power2.out'
                                    }
                                );

                        }
                    );


                /*
                 * 이미지 이동 setter
                 */
                const imageYTo =
                    images.map(
                        function (
                            image,
                            index
                        ) {

                            const durations = [
                                1.8,
                                2.0,
                                1.9
                            ];


                            return gsap
                                .quickTo(
                                    image,
                                    'y',
                                    {
                                        duration:
                                            durations[
                                                index %
                                                durations
                                                    .length
                                            ],

                                        ease:
                                            'power3.out'
                                    }
                                );

                        }
                    );


                states.push({

                    section:
                        section,

                    stage:
                        stage,

                    texts:
                        texts,

                    images:
                        images,

                    textGroups:
                        textGroups,

                    applySectionHeight:
                        applySectionHeight,

                    textOpacityTo:
                        textOpacityTo,

                    textYTo:
                        textYTo,

                    imageYTo:
                        imageYTo

                });

            }
        );


        if (!states.length) {
            return;
        }


        /* ==================================================
           현재 화면 상태 계산
        ================================================== */

        function updateState(state) {

            const rect =
                state.section
                    .getBoundingClientRect();


            const viewportHeight =
                window.innerHeight;


            const scrollDistance =
                Math.max(
                    state.section
                        .offsetHeight -
                    viewportHeight,
                    1
                );


            const progress =
                clamp(
                    -rect.top /
                    scrollDistance,
                    0,
                    1
                );


            const imageCount =
                state.images.length;


            /* ==================================================
               이미지 시작점과 이동 구간 자동 계산
            ================================================== */

            const imageDuration =
                Math.min(
                    0.32,
                    Math.max(
                        0.17,
                        2.5 /
                        imageCount
                    )
                );


            const usableStartRange =
                Math.max(
                    1 -
                    imageDuration,
                    0
                );


            const imageSpacing =
                imageCount > 1
                    ? usableStartRange /
                      (
                          imageCount -
                          1
                      )
                    : 0;


            const imageStarts =
                state.images.map(
                    function (
                        image,
                        index
                    ) {

                        return index *
                            imageSpacing;

                    }
                );


            /* ==================================================
               글씨 전환 기준점 계산

               현재 글씨가 담당하는 마지막 이미지가
               이동 구간의 50% 정도 왔을 때 전환합니다.
            ================================================== */

            const textBoundaries =
                [
                    0
                ];


            state.textGroups.forEach(
                function (
                    group,
                    groupIndex
                ) {

                    /*
                     * 마지막 글씨는 중간 종료점을
                     * 별도로 만들지 않습니다.
                     */
                    if (
                        groupIndex ===
                        state.textGroups.length -
                        1
                    ) {
                        return;
                    }


                    const lastImageIndex =
                        group.endIndex -
                        1;


                    /*
                     * 담당 이미지가 없는 글씨 처리
                     */
                    if (
                        lastImageIndex < 0 ||
                        lastImageIndex >=
                        imageCount
                    ) {

                        textBoundaries
                            .push(
                                textBoundaries[
                                    textBoundaries.length -
                                    1
                                ]
                            );


                        return;

                    }


                    const lastImageStart =
                        imageStarts[
                            lastImageIndex
                        ];


                    /*
                     * 0.50은 이미지 이동 구간의 중간입니다.
                     *
                     * 더 늦게 전환하려면 0.60,
                     * 더 일찍 전환하려면 0.40으로 변경합니다.
                     */
                    const boundary =
                        lastImageStart +
                        imageDuration *
                        0.50;


                    textBoundaries
                        .push(
                            Math.min(
                                boundary,
                                1
                            )
                        );

                }
            );


            /*
             * 마지막 글씨 종료점
             */
            textBoundaries.push(
                1
            );


            /* ==================================================
               글씨가 겹치지 않는 페이드 설정
            ================================================== */

            /*
             * 한 글씨가 사라지고 나타나는 시간입니다.
             */
            const textFadeDuration =
                Math.min(
                    0.032,
                    Math.max(
                        0.012,
                        imageSpacing *
                        0.21
                    )
                );


            /*
             * 이전 글씨가 완전히 사라진 후
             * 다음 글씨가 나타나기 전의 짧은 공백입니다.
             *
             * 이 값 때문에 두 글씨가 겹치지 않습니다.
             */
            const textGap =
                Math.min(
                    0.014,
                    Math.max(
                        0.006,
                        imageSpacing *
                        0.08
                    )
                );


            state.texts.forEach(
                function (
                    text,
                    textIndex
                ) {

                    const segmentStart =
                        textBoundaries[
                            textIndex
                        ] ?? 0;


                    const segmentEnd =
                        textBoundaries[
                            textIndex + 1
                        ] ?? 1;


                    /*
                     * 담당 이미지가 없는 글씨는 숨깁니다.
                     */
                    const group =
                        state.textGroups[
                            textIndex
                        ];


                    if (
                        !group ||
                        group.startIndex >=
                        group.endIndex
                    ) {

                        state.textOpacityTo[
                            textIndex
                        ](
                            0
                        );


                        text.style
                            .visibility =
                            'hidden';


                        return;

                    }


                    /* ==================================================
                       페이드인 구간
                    ================================================== */

                    let fadeInStart;
                    let fadeInEnd;


                    if (
                        textIndex === 0
                    ) {

                        /*
                         * 첫 번째 글씨는 스토리 시작과 함께 등장
                         */
                        fadeInStart =
                            segmentStart;


                        fadeInEnd =
                            Math.min(
                                fadeInStart +
                                textFadeDuration,
                                1
                            );

                    } else {

                        /*
                         * 이전 글씨가 완전히 사라진 후
                         * textGap만큼 기다렸다가 등장
                         */
                        fadeInStart =
                            Math.min(
                                segmentStart +
                                textGap,
                                1
                            );


                        fadeInEnd =
                            Math.min(
                                fadeInStart +
                                textFadeDuration,
                                1
                            );

                    }


                    /* ==================================================
                       페이드아웃 구간
                    ================================================== */

                    let fadeOutStart;
                    let fadeOutEnd;


                    if (
                        textIndex ===
                        state.texts.length -
                        1
                    ) {

                        /*
                         * 마지막 글씨는 스토리 끝에서 사라집니다.
                         */
                        fadeOutEnd =
                            1;


                        fadeOutStart =
                            Math.max(
                                fadeOutEnd -
                                textFadeDuration,
                                fadeInEnd
                            );

                    } else {

                        /*
                         * 다음 전환 기준점보다 textGap만큼 먼저
                         * 현재 글씨를 완전히 숨깁니다.
                         */
                        fadeOutEnd =
                            Math.max(
                                segmentEnd -
                                textGap,
                                fadeInEnd
                            );


                        fadeOutStart =
                            Math.max(
                                fadeOutEnd -
                                textFadeDuration,
                                fadeInEnd
                            );

                    }


                    const fadeIn =
                        smootherStep(
                            range(
                                progress,
                                fadeInStart,
                                fadeInEnd
                            )
                        );


                    const fadeOut =
                        smootherStep(
                            range(
                                progress,
                                fadeOutStart,
                                fadeOutEnd
                            )
                        );


                    let opacity =
                        fadeIn *
                        (
                            1 -
                            fadeOut
                        );


                    /*
                     * 아주 작은 투명도는 0으로 만들어
                     * 글씨 잔상을 완전히 제거합니다.
                     */
                    if (
                        opacity < 0.015
                    ) {

                        opacity =
                            0;

                    }


                    /*
                     * story-section이 화면 밖이면 숨김
                     */
                    if (
                        rect.top >=
                            viewportHeight ||
                        rect.bottom <= 0
                    ) {

                        opacity =
                            0;

                    }


                    /*
                     * 글씨의 세로 움직임
                     */
                    const textY =
                        lerp(
                            28,
                            0,
                            fadeIn
                        ) +
                        lerp(
                            0,
                            -18,
                            fadeOut
                        );


                    /*
                     * opacity는 지연 없이 즉시 적용합니다.
                     */
                    state.textOpacityTo[
                        textIndex
                    ](
                        opacity
                    );


                    /*
                     * 세로 위치만 부드럽게 적용합니다.
                     */
                    state.textYTo[
                        textIndex
                    ](
                        textY
                    );


                    text.style
                        .visibility =
                        opacity > 0
                            ? 'visible'
                            : 'hidden';

                }
            );


            /* ==================================================
               이미지 연속 이동
            ================================================== */

            state.images.forEach(
                function (
                    image,
                    index
                ) {

                    const start =
                        imageStarts[
                            index
                        ];


                    const end =
                        Math.min(
                            start +
                            imageDuration,
                            1
                        );


                    const rawProgress =
                        range(
                            progress,
                            start,
                            end
                        );


                    const imageProgress =
                        smootherStep(
                            rawProgress
                        );


                    /*
                     * 이미지마다 시작점과 종료점을
                     * 조금씩 다르게 설정합니다.
                     */
                    const startHeights = [
                        0.90,
                        0.98,
                        0.94
                    ];


                    const endHeights = [
                        -0.92,
                        -1.02,
                        -0.96
                    ];


                    const startY =
                        viewportHeight *
                        startHeights[
                            index %
                            startHeights.length
                        ];


                    const endY =
                        viewportHeight *
                        endHeights[
                            index %
                            endHeights.length
                        ];


                    const targetY =
                        lerp(
                            startY,
                            endY,
                            imageProgress
                        );


                    state.imageYTo[
                        index
                    ](
                        targetY
                    );

                }
            );

        }


        /* ==================================================
           스크롤 업데이트
        ================================================== */

        let ticking =
            false;


        function update() {

            states.forEach(
                function (state) {

                    updateState(
                        state
                    );

                }
            );


            ticking =
                false;

        }


        function requestUpdate() {

            if (ticking) {
                return;
            }


            ticking =
                true;


            window
                .requestAnimationFrame(
                    update
                );

        }


        window.addEventListener(
            'scroll',
            requestUpdate,
            {
                passive:
                    true
            }
        );


        window.addEventListener(
            'resize',
            function () {

                states.forEach(
                    function (state) {

                        state
                            .applySectionHeight();

                    }
                );


                requestUpdate();

            }
        );


        window.addEventListener(
            'load',
            requestUpdate
        );


        /*
         * Elementor와 이미지 로딩 지연 대응
         */
        window.setTimeout(
            requestUpdate,
            150
        );


        window.setTimeout(
            requestUpdate,
            700
        );


        window.setTimeout(
            requestUpdate,
            1400
        );


        /*
         * 최초 실행
         */
        requestUpdate();

    }


    /*
     * DOM 준비 후 실행
     */
    if (
        document.readyState ===
        'loading'
    ) {

        document
            .addEventListener(
                'DOMContentLoaded',
                initStoryFlexibleGroupMotion
            );

    } else {

        initStoryFlexibleGroupMotion();

    }

})();

JS;


    /*
     * 기존 스니펫이 등록한 GSAP 뒤에 실행
     */
    wp_add_inline_script(
        'seanhaha-gsap',
        $story_js,
        'after'
    );

}


/*
 * 기존 SANC 모션 스니펫보다 뒤에서 실행
 */
add_action(
    'wp_enqueue_scripts',
    'seanhaha_story_flexible_group_motion',
    150
);
